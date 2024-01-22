const mongoose = require("mongoose");

const bookSchema = new mongoose.Schema(
  {
    name: {
      type: String,
      required: true,
    },
    genre: {
      type: mongoose.Schema.Types.ObjectId,
      ref: "genre",
      required: true,
    },
    cover: {
      type: String,
      required: true,
    },
    author: {
      type: String,
      required: true,
    },
    publication: {
      type: String,
      required: true,
    },
    price: {
      type: Number,
      required: true,
    },
    quantity: {
      type: Number,
      default: 0,
    },
    condition: {
      type: String,
      required: true,
      enum: ["new", "old"],
    },
    userId: {
      type: mongoose.Schema.Types.ObjectId,
      ref: "users",
      required: true,
    },
    orders: {
      type: Number,
      default: 0,
    },
    sold: {
      type: Number,
      default: 0,
    },
    approved: {
      type: Number,
      default: 0,
    },
    reviews: [
      {
        userId: {
          type: mongoose.Schema.Types.ObjectId,
          ref: "users",
        },
        rating: {
          type: Number,
          required: true,
        },
        comment: {
          type: String,
        },
        // Add any other fields related to reviews you need
      },
    ],
  },
  { timestamps: true }
);

module.exports = mongoose.model("book", bookSchema);
