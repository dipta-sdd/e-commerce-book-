const mongoose = require('mongoose');

const orderSchema = new mongoose.Schema({
  user: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'users',
    required: true
  },
  items: [
    {
      bookId: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'book',
        required: true
      },
      quantity: {
        type: Number,
        required: true
      },
      // Any additional fields related to the ordered item can be included here
    }
  ],
  totalPrice: {
    type: Number,
    required: true
  },
  status: {
    type: String,
    enum: ['Pending', 'Processing', 'Shipped', 'Delivered'],
    default: 'Pending'
  },
  payment: {
    amount: {
      type: Number
    },
    method: {
      type: String,
      enum: ['Rocket', 'Nagad', 'Bkash']
    },
    transactionID: {
      type: String
    }
  },
  address: {
    country: { type: String },
    division: { type: Number },
    district: { type: Number },
    upazila: { type: Number },
    address: { type: String }
  }
  // Any other fields related to the order can be added here (e.g., shipping details, timestamps, etc.)
}, { timestamps: true });


module.exports = mongoose.model('order', orderSchema);
