const express = require('express');
const cors = require('cors');
const { MongoClient } = require('mongodb');

const app = express();
const port = 3000;

app.use(cors());
app.use(express.json());

// Route d'accueil
app.get('/', (req, res) => {
  res.send('Bienvenue sur le serveur des réservations !');
});

// Connexion MongoDB
const url = 'mongodb://localhost:27017';
const client = new MongoClient(url);
const dbName = 'reservationsDB';

// POST /reservations avec validation simple
app.post('/reservations', async (req, res) => {
  try {
    const reservation = req.body;

    // Validation simple
    if (
      !reservation.nom ||
      typeof reservation.nom !== 'string' ||
      !reservation.date ||
      isNaN(Date.parse(reservation.date)) ||
      !reservation.nombrePersonnes ||
      typeof reservation.nombrePersonnes !== 'number' ||
      reservation.nombrePersonnes <= 0
    ) {
      return res.status(400).send({ message: 'Données de réservation invalides' });
    }

    await client.connect();
    const db = client.db(dbName);
    const collection = db.collection('reservations');

    await collection.insertOne(reservation);

    res.status(201).send({ message: 'Réservation ajoutée avec succès' });
  } catch (error) {
    console.error(error);
    res.status(500).send({ message: 'Erreur serveur' });
  }
});

// GET /reservations : récupérer toutes les réservations
app.get('/reservations', async (req, res) => {
  try {
    await client.connect();
    const db = client.db(dbName);
    const collection = db.collection('reservations');

    const data = await collection.find().toArray();
    res.send(data);
  } catch (error) {
    console.error(error);
    res.status(500).send({ message: 'Erreur serveur' });
  }
});

app.listen(port, () => {
  console.log(`✅ Serveur lancé sur http://localhost:${port}`);
});
