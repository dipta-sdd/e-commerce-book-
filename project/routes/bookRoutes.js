const express = require("express");
const {
  addController,
  genreControler,
  stockController,
  searchController,
  getBook,
  getBookandReview,
  postReview,
  editBook,
} = require("../controllers/bookController");
const authMiddelware = require("../middlewares/authMiddelware");
const router = express.Router();

const multer = require("multer");
const path = require("path");
const storage = multer.diskStorage({
  destination: function (req, file, cb) {
    cb(null, "uploads/"); // Define the directory where images will be stored
  },
  filename: function (req, file, cb) {
    cb(
      null,
      file.fieldname + "-" + Date.now() + path.extname(file.originalname)
    );
  },
});
const upload = multer({ storage: storage });

// add || post
router.post("/add", authMiddelware, upload.single("image"), addController);
// genre || get
router.get("/genre", genreControler);
router.post("/get_details", getBook);
router.post("/get_detail&review", authMiddelware, getBookandReview);
router.post("/search", searchController);
router.post("/stock", authMiddelware, stockController);
router.post("/postReview", authMiddelware, postReview);
router.post("/edit", authMiddelware, editBook);

router.post("/upload", authMiddelware, upload.single("image"), (req, res) => {
  const name = req.body.name; // Access the name field from the form
  console.log(req.body);
  if (!req.file) {
    return res.status(401).send("No image uploaded.");
  }

  // You can perform actions like saving the file path to a database or processing the image here
  // For now, let's just respond with a success message
  res.status(200).send("Image uploaded successfully");
});

module.exports = router;
