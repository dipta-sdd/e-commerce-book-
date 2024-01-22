const userModel = require("../models/userModel");
const bookModel = require("../models/bookModel");
const Order = require("../models/orderModel");
const orderModel = require("../models/orderModel");
const paymentModel = require("../models/paymentModels");

const checkout = async (req, res) => {
  try {
    const { userId, order } = req.body;

    const user = await userModel.findById(userId);

    const bookIds = order.map((item) => item.bookId);
    const books = await bookModel.find({ _id: { $in: bookIds } });
    console.log(books);

    const items = order.map((item) => {
      const book = books.find((book) => book._id.toString() === item.bookId);
      return {
        bookId: item.bookId,
        quantity: item.quantity,
        price: book ? book.price : 0, // Assign book price or default to 0 if book not found
      };
    });

    for (const item of order) {
      const bookId = item.bookId;
      const quantity = item.quantity;

      await bookModel.updateOne(
        { _id: bookId },
        { $inc: { orders: quantity } } // Increment the 'orders' field by the order quantity
      );
      // Find the index of the book in the user's cart
      const cartItemIndex = user.cart.books.findIndex(
        (book) => book.bookId.toString() === bookId
      );
      if (cartItemIndex !== -1) {
        // Reduce the quantity in the user's cart by the ordered quantity
        user.cart.books[cartItemIndex].quantity -= quantity;
        if (user.cart.books[cartItemIndex].quantity <= 0) {
          // Remove the book from the cart if the quantity becomes zero or negative
          user.cart.books.splice(cartItemIndex, 1);
        }
      }
    }

    const totalPrice = items.reduce(
      (total, item) => total + item.price * item.quantity,
      0
    );

    const newOrder = new Order({
      user: userId,
      items: items,
      totalPrice: totalPrice,
      status: "Pending",
    });

    // Save the new order to the database
    const savedOrder = await newOrder.save();

    // Update user's orders array with the new order ID
    user.orders.push(savedOrder._id);
    // user.orders.push({orderId: savedOrder._id});
    await user.save();

    res.status(200).json({
      success: true,
      message: "Order placed successfully",
      order: savedOrder,
    });
  } catch (error) {
    res.status(500).send({
      success: false,
      message: "Error in API",
      error: error.message,
    });
  }
};
const checkout2 = async (req, res) => {
  try {
    const { userId, order } = req.body;

    const user = await userModel.findById(userId);

    const bookIds = order.map((item) => item.bookId);
    const books = await bookModel.find({ _id: { $in: bookIds } });
    console.log(books);

    const items = order.map((item) => {
      const book = books.find((book) => book._id.toString() === item.bookId);
      return {
        bookId: item.bookId,
        quantity: item.quantity,
        price: book ? book.price : 0, // Assign book price or default to 0 if book not found
      };
    });

    for (const item of order) {
      const bookId = item.bookId;
      const quantity = item.quantity;

      await bookModel.updateOne(
        { _id: bookId },
        { $inc: { orders: quantity } } // Increment the 'orders' field by the order quantity
      );
      // // Find the index of the book in the user's cart
      // const cartItemIndex = user.cart.books.findIndex(book => book.bookId.toString() === bookId);
      // if (cartItemIndex !== -1) {
      //   // Reduce the quantity in the user's cart by the ordered quantity
      //   user.cart.books[cartItemIndex].quantity -= quantity;
      //   if (user.cart.books[cartItemIndex].quantity <= 0) {
      //     // Remove the book from the cart if the quantity becomes zero or negative
      //     user.cart.books.splice(cartItemIndex, 1);
      //   }
      // }
    }

    const totalPrice = items.reduce(
      (total, item) => total + item.price * item.quantity,
      0
    );

    const newOrder = new Order({
      user: userId,
      items: items,
      totalPrice: totalPrice,
      status: "Pending",
    });

    // Save the new order to the database
    const savedOrder = await newOrder.save();

    // Update user's orders array with the new order ID
    user.orders.push(savedOrder._id);
    // user.orders.push({orderId: savedOrder._id});
    await user.save();

    res.status(200).json({
      success: true,
      message: "Order placed successfully",
      order: savedOrder,
    });
  } catch (error) {
    res.status(500).send({
      success: false,
      message: "Error in API",
      error: error.message,
    });
  }
};
const myOrder = async (req, res) => {
  try {
    const { filter, filters, userId } = req.body;
    console.log(filters);
    if (filter) {
      user = await userModel.findOne({ _id: userId }).populate({
        path: "orders",
        options: { sort: { createdAt: -1 } },
        match: { status: filter },
        populate: {
          path: "items.bookId",
          populate: {
            path: "genre",
          },
        },
      });
    } else if (filters) {
      user = await userModel.findOne({ _id: userId }).populate({
        path: "orders",
        options: { sort: { createdAt: -1 } },
        match: { status: { $in: filters } },
        populate: {
          path: "items.bookId",
          populate: {
            path: "genre",
          },
        },
      });
    } else {
      user = await userModel.findOne({ _id: userId }).populate({
        path: "orders",
        options: { sort: { createdAt: -1 } },
        populate: {
          path: "items.bookId",
          populate: {
            path: "genre",
          },
        },
      });
    }
    res.status(200).json({
      success: true,
      order: user.orders,
    });
  } catch (error) {
    res.status(500).send({
      success: false,
      message: "Error in API",
      error: error.message,
    });
  }
};

const vendorOrder = async (req, res) => {
  try {
    const { userId } = req.body; // Assuming you pass the vendor's userId through params

    // Find books by userId (vendor) and populate the 'genre' field
    const books = await bookModel.find({ userId }).populate("genre");

    const bookIds = books.map((book) => book._id); // Extract book IDs associated with the vendor

    // Find orders that contain the books associated with the vendor
    const orders = await orderModel
      .find({ "items.bookId": { $in: bookIds } })
      .populate("items.bookId");

    // Prepare book quantity mapping for vendor's books in orders
    const bookQuantityMap = {};

    orders.forEach((order) => {
      order.items.forEach((item) => {
        if (1) {
          const bookId = item.bookId._id.toString();
          const book = item.bookId;

          if (!bookQuantityMap[bookId]) {
            bookQuantityMap[bookId] = {
              bookDetails: book,
              quantity: 0,
            };
          }
          bookQuantityMap[bookId].quantity += item.quantity;
        }
      });
    });
    // console.log(bookQuantityMap)
    // Now 'bookQuantityMap' contains book IDs as keys and their details along with total quantity in orders
    res.status(200).json({ books: bookQuantityMap });
  } catch (error) {
    console.error("Error fetching vendor orders:", error);
    res.status(500).json({ error: "Server Error" });
  }
};

const find = async (req, res) => {
  try {
    const { userId, orderId } = req.body;
    const order = await orderModel
      .findOne({ _id: orderId, user: userId })
      .populate({ path: "items.bookId", populate: { path: "genre" } });
    if (!order) {
      return res.status(200).send({
        success: false,
        message: "Order not exists",
      });
    }
    updateStatus(orderId);
    res.status(200).json({
      success: true,
      order: order,
    });
  } catch (error) {
    res.status(500).send({
      success: false,
      message: "Error in API",
      error: error.message,
    });
  }
};

const payment = async (req, res) => {
  try {
    const { userId, orderId, method, amount, transactionID } = req.body;
    const order = await orderModel.findById(orderId);

    if (!order) {
      return res
        .status(200)
        .json({ success: false, message: "Order not found" });
    }
    if (order.user.toString() !== userId) {
      return res
        .status(200)
        .json({ success: false, message: "Unauthorized access to order" });
    }
    const payment = await paymentModel.findOne({
      transactionId: transactionID,
      method: method,
    });
    console.log(payment.status);
    if (!payment) {
      return res
        .status(200)
        .json({ success: false, message: "Wrong TransactionID or Method" });
    } else if (payment.status == "false") {
      return res
        .status(200)
        .json({ success: false, message: "Already Used Transaction" });
    } else if (order.totalPrice != payment.amount) {
      return res
        .status(200)
        .json({ success: false, message: "Transaction amount didn't match." });
    }

    order.payment = {
      amount: amount,
      method: method,
      transactionID: transactionID,
    };

    payment.status = true;
    const updatePayment = await payment.save();
    const updatedOrder = await order.save();
    updateStatus(orderId);
    res.status(200).json({
      success: true,
      message: "Payment successful",
      order: updatedOrder,
      payment: updatePayment,
    });
  } catch (error) {
    res.status(500).send({
      success: false,
      message: "Error in API",
      error: error.message,
    });
  }
};

const address = async (req, res) => {
  try {
    const { userId, orderId, address } = req.body;

    // Find the order by ID
    const order = await orderModel.findById(orderId);

    if (!order) {
      return res.status(404).json({ message: "Order not found" });
    }

    // Ensure the order belongs to the user before updating payment details
    if (order.user.toString() !== userId) {
      return res.status(403).json({ message: "Unauthorized access to order" });
    }

    // Update the payment details in the order
    order.address = address;

    // Save the updated order with payment details
    const updatedOrder = await order.save();

    res.status(200).json({
      success: true,
      message: "Address successfully updated.",
      order: updatedOrder,
    });
  } catch (error) {
    res.status(500).send({
      success: false,
      message: "Error in API",
      error: error.message,
    });
  }
};

const updateStatus = async (orderId) => {
  try {
    const order = await orderModel.findById(orderId).populate("items.bookId");
    if (!order) {
      console.log("Order not found");
      return;
    }
    fulfil = 0;
    if (order.address.country != "Bangladesh" || order.status == "Processing") {
      fulfil++;
      return;
    }

    order.items.map((book) => {
      if (Number(book.quantity) > Number(book.bookId.approved)) {
        console.log(book.quantity);
        fulfil++;
      }
    });
    if (fulfil == 0) {
      for (const item of order.items) {
        const book = await bookModel.findById(item.bookId._id);
        console.log(book);
        if (book) {
          book.approved -= item.quantity;
          book.sold += item.quantity;
          await book.save(); // Save the updated book
        }
      }
      order.status = "Processing";
      await order.save();
      console.log("Order Status updated to Processing");
    }
  } catch (error) {
    console.error("Error updating order status:", error);
  }
};

module.exports = {
  address,
  payment,
  find,
  vendorOrder,
  myOrder,
  checkout,
  checkout2,
  updateStatus,
};
