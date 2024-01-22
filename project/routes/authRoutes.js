const express = require("express");
const {
  registerController,
  loginController,
  currentUserController,
  updateUser,
  uploader,
  receiveFile,
  cartPush,
  cartCount,
  cartGet,
  checkout,
  passwordChange,
  otpController,
  resendOTP,
  passwordReset,
  userDetails,
  canReview,
  postReview,
} = require("../controllers/authController");
const authMiddelware = require("../middlewares/authMiddelware");

const router = express.Router();
// register || post
router.post("/signup", registerController);
// login || post
router.post("/login", loginController);
// current user || get
router.get("/current-user", authMiddelware, currentUserController);
// update || post
router.post("/update-user", authMiddelware, updateUser);
//fo=ile
router.post("/file", receiveFile);
// cart add || post
router.post("/cart", authMiddelware, cartPush);
// cart add || post
router.post("/cart_list", authMiddelware, cartGet);
// cart count || post
router.post("/cart_count", authMiddelware, cartCount);
router.post("/pass", authMiddelware, passwordChange);
router.post("/verify", otpController);
router.post("/resend", resendOTP);
router.post("/reset", passwordReset);
router.post("/resget_detail&reviewet", authMiddelware, passwordReset);
router.post("/get_details", userDetails);
router.post("/get_detail&review", authMiddelware, canReview);
router.post("/postReview", authMiddelware, postReview);

module.exports = router;
