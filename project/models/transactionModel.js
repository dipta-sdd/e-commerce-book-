const mongoose = require("mongoose");

const transactionSchema = new mongoose.Schema(
  {
    userId: {
      type: mongoose.Schema.Types.ObjectId,
      ref: "users",
      required: true,
    },
    amount: {
      type: Number,
      required: true,
    },
    status: {
      type: String,
      enum: ["Pending", "Approved", "Canceled"],
      default: "Pending",
      required: true,
    },
    method: {
      type: String,
      enum: ["Bkash", "Rocket", "Nagad"],
      required: true,
    },
    ac: {
      type: Number,
      required: true,
    },
    // Any other fields related to the order can be added here (e.g., shipping details, timestamps, etc.)
  },
  { timestamps: true }
);

module.exports = mongoose.model("transaction", transactionSchema);
