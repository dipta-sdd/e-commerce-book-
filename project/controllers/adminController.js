const userModel = require("../models/userModel");
const bookModel = require("../models/bookModel");
const orderModel = require("../models/orderModel");
const {
  startOfWeek,
  endOfWeek,
  eachDayOfInterval,
  subDays,
} = require("date-fns");
const paymentModels = require("../models/paymentModels");

const pendingOrder = async (req, res) => {
  try {
    // Find all orders with status 'Pending'
    const pendingOrders = await orderModel
      .find({ status: "Pending" })
      .populate("user")
      .populate({ path: "items.bookId", populate: { path: "genre" } })
      .sort({ createdAt: "desc" });
    // Populate the 'bookId' field within the 'items' array if applicable

    res.status(200).json({ order: pendingOrders });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: "Internal server error" });
  }
};
const findOrder = async (req, res) => {
  try {
    // Find all orders with status 'Pending'
    const filter = req.body.filter;
    if (filter) {
      Orders = await orderModel
        .find({ status: filter })
        .populate("user")
        .populate({ path: "items.bookId", populate: { path: "genre" } })
        .sort({ createdAt: "desc" });
    } else {
      Orders = await orderModel
        .find()
        .populate("user")
        .populate({ path: "items.bookId", populate: { path: "genre" } })
        .sort({ createdAt: "desc" });
    }

    // Populate the 'bookId' field within the 'items' array if applicable

    res.status(200).json({ order: Orders });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: "Internal server error" });
  }
};

const approveOrder = async (req, res) => {
  try {
    const orderId = req.body.orderId;

    // Get the order from the database
    const order = await orderModel.findById(orderId);
    if (!order) {
      return res.status(404).json({ message: "Order not found" });
    }

    // Get all the books from the database
    const books = await bookModel.find({
      _id: { $in: order.items.map((item) => item.bookId) },
    });

    // Update book quantities based on the order
    for (const item of order.items) {
      const book = books.find((book) => book._id.equals(item.bookId));
      if (book) {
        book.approved -= item.quantity;
        book.sold += item.quantity;
        await book.save(); // Save the updated book
      }
    }

    order.status = "Processing";
    await order.save();

    res.status(200).json({ message: "Order approved successfully" });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: "Internal server error" });
  }
};

const orderStatus = async (req, res) => {
  try {
    const { orderId, status } = req.body;
    const order = await orderModel.findById(orderId);
    if (!order) {
      return res.status(404).json({ message: "Order not found" });
    }
    order.status = status;
    // if order.status == Delivered
    // find all the books and find total price of induvisual book
    await order.save(); // Save the updated order status
    if (status === "Delivered") {
      for (const item of order.items) {
        const book = await bookModel.findById(item.bookId);
        if (book) {
          const user = await userModel.findById(book.userId);
          if (user) {
            user.balance += item.quantity * book.price;
            await user.save();
          }
        }
      }

      res.status(200).json({ message: "Order updated successfully", order });
    } else {
      res
        .status(200)
        .json({ message: "Order status updated to " + status, order });
    }
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: "Internal server error" });
  }
};

const weeklyStatOrderCount = async (req, res) => {
  try {
    // Get the start and end dates of the current week
    const currentDate = new Date();
    const endOfCurrentWeek = currentDate;
    const daysOfCurrentWeek = eachDayOfInterval({
      start: subDays(currentDate, 6), // Set start date to 7 days before the current date
      end: endOfCurrentWeek,
    });
    console.log(daysOfCurrentWeek);

    // Initialize arrays to store daily data
    const daysArray = [];
    const deliveredCountsArray = [];
    const createdCountsArray = [];

    // Iterate over each day and count orders
    for (const day of daysOfCurrentWeek) {
      const nextDay = new Date(day);
      nextDay.setDate(nextDay.getDate() + 1);

      const formattedDate = day.toISOString().split("T")[0];
      daysArray.push(formattedDate);

      const createdCount = await orderModel.countDocuments({
        createdAt: { $gte: day, $lt: nextDay },
      });
      createdCountsArray.push(createdCount);

      const deliveredCount = await orderModel.countDocuments({
        status: "Delivered",
        updatedAt: { $gte: day, $lt: nextDay },
      });
      deliveredCountsArray.push(deliveredCount);
    }

    res.status(200).json({
      days: daysArray,
      deliveredCounts: deliveredCountsArray,
      createdCounts: createdCountsArray,
    });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: "Internal server error" });
  }
};

const weeklyStatOrderPrice = async (req, res) => {
  try {
    const currentDate = new Date();
    const endOfCurrentWeek = currentDate;
    const daysOfCurrentWeek = eachDayOfInterval({
      start: subDays(currentDate, 6), // Set start date to 7 days before the current date
      end: endOfCurrentWeek,
    });
    // console.log(daysOfCurrentWeek);
    const getTotalPriceSum = async (filter) => {
      const orders = await orderModel.find(filter);
      return orders.reduce((sum, order) => sum + order.totalPrice, 0);
    };

    const getOrderSumsForDay = async (day) => {
      const nextDay = new Date(day);
      nextDay.setDate(nextDay.getDate() + 1);

      const formattedDate = day.toISOString().split("T")[0];

      const createdTotalPriceSum = await getTotalPriceSum({
        createdAt: { $gte: day, $lt: nextDay },
      });

      const deliveredTotalPriceSum = await getTotalPriceSum({
        status: "Delivered",
        updatedAt: { $gte: day, $lt: nextDay },
      });

      return {
        date: formattedDate,
        createdTotalPriceSum,
        deliveredTotalPriceSum,
      };
    };

    const daysArray = [];
    const orderSumsArrayD = [];
    const orderSumsArrayC = [];

    for (const day of daysOfCurrentWeek) {
      const orderSumsForDay = await getOrderSumsForDay(day);
      daysArray.push(orderSumsForDay.date);
      orderSumsArrayC.push(orderSumsForDay.createdTotalPriceSum);
      orderSumsArrayD.push(orderSumsForDay.deliveredTotalPriceSum);
    }

    res.status(200).json({
      days: daysArray,
      created: orderSumsArrayC,
      delivered: orderSumsArrayD,
    });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: "Internal server error" });
  }
};

const weeklyStatBooks = async (req, res) => {
  try {
    const sevenDaysAgo = new Date();
    sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);
    const thisWeekOrders = await orderModel
      .find({
        createdAt: { $gte: sevenDaysAgo },
      })
      .populate("items.bookId");

    const bookQuantitiesMap = new Map();

    thisWeekOrders.forEach((order) => {
      order.items.forEach((item) => {
        const bookId = item.bookId._id;
        const quantity = item.quantity;
        if (bookQuantitiesMap.has(bookId)) {
          bookQuantitiesMap.set(
            bookId,
            bookQuantitiesMap.get(bookId) + quantity
          );
        } else {
          bookQuantitiesMap.set(bookId, quantity);
        }
      });
    });

    // Sort the books based on quantities in descending order
    const sortedBooks = Array.from(bookQuantitiesMap.entries())
      .sort((a, b) => b[1] - a[1])
      .slice(0, 5);

    const bookIds = sortedBooks.map(([bookId, _]) => bookId);
    const names = [];
    const quantities = sortedBooks.map(([_, quantity]) => quantity);

    for (const bookId of bookIds) {
      const book = await bookModel.findById(bookId);
      if (book) {
        names.push(book.name);
      }
    }

    res.status(200).json({
      bookIds,
      names,
      quantities,
    });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: "Internal server error" });
  }
};

const users = async (req, res) => {
  try {
    const { name, filter } = req.body;
    const filters = {
      all: ["admin", "library", "buyer", "user", "seller", "publication"],
      user: "user",
      seller: "seller",
      library: "library",
      publication: "publication",
      admin: "admin",
    };
    const users = await userModel.find({
      $or: [
        { name: { $regex: name, $options: "i" } },
        { email: { $regex: name, $options: "i" } },
      ],
      role: { $in: filters[filter] },
    });
    res.status(200).json({ users });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: "Internal server error" });
  }
};

const payment = async (req, res) => {
  try {
    const { userId, transactionId, amount, status, method } = req.body;

    // Create a new payment instance
    const newPayment = new paymentModels({
      userId,
      transactionId,
      amount,
      status,
      method,
    });

    // Save the payment to the database
    const savedPayment = await newPayment.save();

    res.status(201).json({
      success: true,
      message: "Payment inserted successfully",
      payment: savedPayment,
    });
  } catch (error) {
    console.error(error);
    res.status(500).json({
      success: false,
      message: "Error in API",
      error: error.message,
    });
  }
};

module.exports = {
  orderStatus,
  findOrder,
  approveOrder,
  pendingOrder,
  weeklyStatOrderCount,
  weeklyStatOrderPrice,
  weeklyStatBooks,
  users,
  payment,
};
