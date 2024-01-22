const mongoose = require("mongoose");

const paymentsSchema = new mongoose.Schema(
  {
    userId: {
      type: mongoose.Schema.Types.ObjectId,
      ref: "users",
      required: true,
    },
    transactionId: {
      type: String,
      required: true,
      unique: true,
    },
    amount: {
      type: Number,
      required: true,
    },
    status: {
      type: Boolean,
      default: false,
      required: true,
    },
    method: {
      type: String,
      enum: ["Bkash", "Rocket", "Nagad"],
      required: true,
    },
    // Any other fields related to the order can be added here (e.g., shipping details, timestamps, etc.)
  },
  { timestamps: true }
);

module.exports = mongoose.model("payment", paymentsSchema);
