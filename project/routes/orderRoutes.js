const express = require("express");
const {
  checkout,
  myOrder,
  vendorOrder,
  find,
  payment,
  address,
  checkout2,
} = require("../controllers/orderController");
const authMiddelware = require("../middlewares/authMiddelware");
const router = express.Router();

// cart count || post
router.post("/checkout2", authMiddelware, checkout2);
router.post("/checkout", authMiddelware, checkout);
router.post("/my_order", authMiddelware, myOrder);
router.post("/my_order/vendor", authMiddelware, vendorOrder);
router.post("/find", authMiddelware, find);
router.post("/payment", authMiddelware, payment);
router.post("/address", authMiddelware, address);

module.exports = router;
