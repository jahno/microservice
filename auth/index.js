const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql2');
const jwt = require('jsonwebtoken');
const bcrypt = require('bcrypt');
const cors = require('cors')

const app = express();
const port = process.env.PORT || 3000;

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.use(cors());

const conn = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'microauth',
});

app.post('/login', (req, res) => {
  const { email, password } = req.body;
  conn.query('SELECT * FROM users WHERE email = ?', [email], (err, results) => {
    if (err) throw err;

    if (results.length > 0) {
      const user = results[0];
      bcrypt.compare(password, user.password, (err, result) => {
        if (result) {
          const token = jwt.sign({ id: user.id }, 'secret_key', { expiresIn: '1h' });
          res.json({ token });
        } else {
          res.status(401).json({ message: 'Mot de passe incorrect' });
        }
      });
    } else {
      res.status(404).json({ message: 'Utilisateur introuvable' });
    }
  });
});

function verifyToken(req, res, next) {
  const token = req.headers.authorization;
  if (!token) {
    return res.status(401).json({ message: 'Token non fourni' });
  }

  jwt.verify(token, 'secret_key', (err, decoded) => {
    if (err) {
      return res.status(401).json({ message: 'Token invalide' });
    }
    req.userId = decoded.id;
    next();
  });
}

app.get('/profile', verifyToken, (req, res) => {
  const userId = req.userId;
  conn.query('SELECT * FROM users WHERE id = ?', [userId], (err, results) => {
    if (err) throw err;
    const user = results[0];
    res.json({ user });
  });
});


app.post('/register', (req, res) => {
    const { name, email, password } = req.body;
  
    conn.query('SELECT * FROM users WHERE email = ?', [email], (err, results) => {
      if (err) throw err;
  
      if (results.length > 0) {
        res.status(409).json({ message: 'Cet email est déjà utilisé' });
      } else {
        bcrypt.hash(password, 10, (err, hash) => {
          if (err) throw err;
  
          conn.query('INSERT INTO users (name, email, password) VALUES (?, ?, ?)', [name, email, hash], (err, results) => {
            if (err) throw err;
  
            const userId = results.insertId;
            const token = jwt.sign({ id: userId }, 'secret_key', { expiresIn: '1h' });
            res.json({ token });
          });
        });
      }
    });
  });

  
app.listen(port, () => {
  console.log(`Serveur en écoute sur le port ${port}`);
});
