const express = require("express");
const adminMiddelware = require("../middlewares/adminMiddelware");
const {
  pendingOrder,
  approveOrder,
  findOrder,
  orderStatus,
  getOrderCountsByDayThisWeek,
  bookstat,
  weeklyStat,
  weeklyStatBooks,
  getOrderSumsByDayThisWeek,
  weeklyStatOrderCount,
  weeklyStatOrderPrice,
  users,
  payment,
} = require("../controllers/adminController");
const router = express.Router();

// cart count || post
router.post("/pendingOrder", adminMiddelware, pendingOrder);
router.post("/findOrder", adminMiddelware, findOrder);
router.post("/approveOrder", adminMiddelware, approveOrder);
router.post("/orderStatus", adminMiddelware, orderStatus);
router.get("/dashboard", adminMiddelware, weeklyStatOrderCount);
router.get("/dashboard3", adminMiddelware, weeklyStatOrderPrice);
router.get("/dashboard2", adminMiddelware, weeklyStatBooks);
router.post("/users", adminMiddelware, users);
router.post("/payment", adminMiddelware, payment);

module.exports = router;
