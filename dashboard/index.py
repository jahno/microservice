from kafka import KafkaConsumer
import json

# Créer un consommateur Kafka pour la file d'attente "nouveaux-articles"
consumer = KafkaConsumer("nouveaux-articles", bootstrap_servers=['localhost:9092'])

# Traiter chaque message reçu
for message in consumer:
    # Extraire les détails de l'article du message JSON
    article = json.loads(message.value.decode('utf-8'))
    nom = article["nom"]
    description = article["description"]
    # Mettre à jour les données de commande en conséquence
    mise_a_jour_commande(nom, description)
