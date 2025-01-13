import express from 'express';
import bodyParser from 'body-parser';
import nodemailer from 'nodemailer';
import mysql from 'mysql2';

const cors = require('cors');
app.use(cors());

const app = express();
const port = 8000;

// Middleware
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Configuración de la base de datos
const db = mysql.createConnection({
  host: '127.0.0.1',
  user: 'root', // Cambia a tu usuario
  password: 'root', // Cambia a tu contraseña
  database: 'laravel', // Cambia al nombre de tu base de datos
});

// Conectar a la base de datos
db.connect((err) => {
  if (err) {
    console.error('Error al conectar a la base de datos:', err);
    return;
  }
  console.log('Conexión a la base de datos exitosa');
});

// Ruta para enviar correos
app.post('/send-email', (req, res) => {
  const { id, name, email, message } = req.body;

  // Consulta para obtener el correo de destino según el ID del usuario
  const query = 'SELECT email FROM users WHERE id = ?'; // Cambia 'users' por tu tabla de usuarios

  db.query(query, [id], async (err, results) => {
    if (err) {
      console.error('Error al realizar la consulta:', err);
      return res.status(500).send('Error al consultar la base de datos');
    }

    if (results.length === 0) {
      return res.status(404).send('No se encontró el correo de destino');
    }

    const destinationEmail = results[0].email;

    // Configuración de nodemailer
    const transporter = nodemailer.createTransport({
      service: 'gmail',
      auth: {
        user: 'tinderkete@gmail.com',
        pass: 'bnqforbrlqhublgz',
      },
    });

    // Configurar el correo
    const mailOptions = {
      from: email,
      to: destinationEmail, // Correo recuperado de la base de datos
      subject: `${name} erabiltzaileren mezu berria`,
      text: message,
    };

    try {
      await transporter.sendMail(mailOptions);
      res.status(200).send('Correo enviado correctamente');
    } catch (error) {
      console.error('Error al enviar el correo:', error);
      res.status(500).send('Error al enviar el correo');
    }
  });
});

// Arrancar el servidor
app.listen(port, () => {
  console.log(`Servidor escuchando en http://localhost:${port}`);
});
