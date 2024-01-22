const transactionModel = require("../models/transactionModel");
const bookModel = require("../models/bookModel");
const orderModel = require("../models/orderModel");

const request = async (req, res) => {
  try {
    console.log(req.body);
    const transaction = new transactionModel(req.body);
    await transaction.save();
    return res.status(201).send({
      success: true,
      message: "Request successfully submited.",
    });
  } catch (error) {
    res.status(500).send({
      success: false,
      message: "Error in API",
      error,
    });
  }
};

const history = async (req, res) => {
  try {
    const userId = req.body.userId;
    const trans = await transactionModel.find({ userId: userId });
    if (!trans) {
      res.status(200).send({
        success: false,
        message: "No transaction found",
      });
    }
    res.status(200).send({
      success: true,
      trans,
    });
  } catch (error) {
    res.status(500).send({
      success: false,
      message: "Error in API",
      error,
    });
  }
};

module.exports = { request, history };
