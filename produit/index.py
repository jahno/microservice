from flask import Flask, jsonify, request
from flask_mysqldb import MySQL
#from kafka import KafkaProducer
from kafka import KafkaConsumer
import json

app = Flask(__name__)
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = ''
app.config['MYSQL_DB'] = 'microproduit'

mysql = MySQL(app)

# Créer un producteur Kafkap
#producer = KafkaProducer(bootstrap_servers=['localhost:9092'])

# Créer un consumer Kafkap
consumer = KafkaConsumer('nouvelle-commande', bootstrap_servers=['localhost:9092'], value_deserializer=lambda m: json.loads(m.decode('utf-8')))

 
for message in consumer:
    # Récupérer les informations du produit depuis le message Kafka
    print(message.value)
    # produit = message.value
    # nom = produit['nom']
    # quantite = produit['quantite']
    
    # # Mettre à jour la quantité du produit dans la base de données
    # cur = mysql.connection.cursor()
    # cur.execute("UPDATE produits SET quantite = %s WHERE nom = %s", (quantite, nom))
    # mysql.connection.commit()
    # cur.close()



@app.route('/produits', methods=['GET'])
def get_products():
    cur = mysql.connection.cursor()
    cur.execute("SELECT * FROM produits")
    data = cur.fetchall()
    products = []
    for product in data:
        products.append({'id': product[0], 'nom': product[1], 'description': product[2], 'prix': product[3]})
    return jsonify({'produits': products})

@app.route('/produits', methods=['POST'])
def add_product():
    nom = request.json['nom']
    description = request.json['description']
    prix = request.json['prix']
    cur = mysql.connection.cursor()
    cur.execute("INSERT INTO produits(nom, description, prix) VALUES (%s, %s, %s)", (nom, description, prix))
    mysql.connection.commit()
    cur.close()
    #je publie
    # Créer un dictionnaire avec les détails de l'article
    article = {"nom": "nom", "description": "description"}
    # Convertir le dictionnaire en chaîne JSON pour l'envoi par Kafka
    message = json.dumps(article).encode('utf-8')
    # Envoyer le message à la file d'attente "nouveaux-articles"
   # producer.send("nouveaux-articles", message)



    return jsonify({'message': 'Product ajouter avec succès'})

if __name__ == '__main__':
    app.run(debug=True)
