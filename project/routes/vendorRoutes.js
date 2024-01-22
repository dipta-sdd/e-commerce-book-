const express = require("express");
const {
  sell,
  myOrder,
  currentOrder,
  completeOrder,
  weeklyStatOrderPrice,
  weeklyStatBooks,
} = require("../controllers/vendorController");
const authMiddelware = require("../middlewares/authMiddelware");
const router = express.Router();

// cart count || post
router.post("/sell", authMiddelware, sell);
router.post("/myOrder", authMiddelware, myOrder);
router.post("/currentOrder", authMiddelware, currentOrder);
router.post("/completeOrder", authMiddelware, completeOrder);
router.get("/dashboard", authMiddelware, weeklyStatBooks);
router.get("/dashboard2", authMiddelware, weeklyStatOrderPrice);

module.exports = router;
