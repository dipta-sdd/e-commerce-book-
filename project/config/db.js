const mongoose = require("mongoose");
const colors = require("colors");

const connectDB = async () => {
  try {
    await mongoose.connect(
      "mongodb+srv://neub:neub2580@cbss.u6hjkqr.mongodb.net/cbss?retryWrites=true&w=majority"
    );
    // await mongoose.connect(process.env.MONGO_URL);
    console.log(
      `Connected to Mongodb Database ${mongoose.connection.host}`.bgGreen
    );
  } catch (error) {
    console.log(`Mongodb Database Error ${error}`.bgRed);
  }
};

module.exports = connectDB;
