const express = require("express");
const authMiddelware = require("../middlewares/authMiddelware");
const { request, history } = require("../controllers/transactionController");
const router = express.Router();

// cart count || post
router.post("/request", authMiddelware, request);
router.post("/history", authMiddelware, history);

module.exports = router;
