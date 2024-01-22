const express = require("express");
const dotenv = require("dotenv");
const colors = require("colors");
const morgan = require("morgan");
const cors = require("cors");
const connectDB = require("./config/db");
const path = require("path"); //acces uploded file

// dot config
// xcbnmn
// xcbnmn
// xcbnmn
// xcbnmn
// xcbnmn
dotenv.config();

// database
connectDB();

// rest object
const app = express();

// middlewares
app.use(express.json());
app.use(cors());
app.use(morgan("dev"));
// Serve static files from the 'uploads' directory
app.use("/image", express.static(path.join(__dirname, "uploads")));

// router
app.use("/api/v1/upload", require("./routes/upload"));
app.use("/api/v1/auth", require("./routes/authRoutes"));
app.use("/api/v1/book", require("./routes/bookRoutes"));
app.use("/api/v1/order", require("./routes/orderRoutes"));
app.use("/api/v1/vendor", require("./routes/vendorRoutes"));
app.use("/api/v1/admin", require("./routes/adminRoutes"));
app.use("/api/v1/transaction", require("./routes/transactionRoutes"));

const PORT = process.env.PORT || 8000;

app.listen(PORT, () => {
  console.log(
    `Node server running in ${process.env.DEV_MODE} Mode on Port ${process.env.PORT}`
  );
});
