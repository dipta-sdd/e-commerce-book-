const mongoose = require("mongoose");

const userSchema = new mongoose.Schema(
  {
    role: {
      type: String,
      required: [true, "role is required"],
      enum: ["admin", "library", "buyer", "user", "seller", "publication"],
    },
    name: {
      type: String,
      required: true,
    },
    email: {
      type: String,
      required: [true, "email is required"],
      unique: true,
    },
    phone: {
      type: String,
      required: false,
    },
    address: {
      country: { type: String },
      division: { type: Number },
      district: { type: Number },
      upazila: { type: Number },
      address: { type: String },
    },
    password: {
      type: String,
      required: [true, "password is required"],
    },
    balance: {
      type: Number,
      default: 0, // Default balance value when a new user is created
    },
    cart: {
      books: [
        {
          bookId: { type: mongoose.Schema.Types.ObjectId, ref: "book" },
          quantity: { type: Number, default: 1 },
        },
      ],
    },
    otp: {
      code: {
        type: String,
        default: null,
      },
      expiry: {
        type: Date,
        default: null,
      },
    },
    status: {
      type: String,
      enum: ["pending", "approved", "blobked"],
      default: "pending",
      required: "true",
    },
    orders: [{ type: mongoose.Schema.Types.ObjectId, ref: "order" }],
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
      },
    ],
  },
  { timestamps: true }
);

module.exports = mongoose.model("users", userSchema);
