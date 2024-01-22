const userModel = require("../models/userModel");
const bookModel = require("../models/bookModel");
const orderModel = require("../models/orderModel");
const transactionModel = require("../models/transactionModel");
const { default: mongoose } = require("mongoose");
const { eachDayOfInterval, subDays } = require("date-fns");
const { updateStatus } = require("./orderController");

const sell = async (req, res) => {
  try {
    const { userId, bookId, count } = req.body;
    console.log(req.body);
    await bookModel.updateOne(
      { _id: bookId, userId: userId }, // Find the book by ID and user ID
      {
        $inc: {
          quantity: -count,
          approved: +count,
          orders: -count,
        },
      } // Decrement quantity, increment sold
    );
    const orders = await orderModel.find({
      items: {
        $elemMatch: {
          bookId: bookId,
        },
      },
    });
    orders.map((order) => {
      updateStatus(order._id);
    });

    res.status(200).json({
      success: true,
      message: `Sold ${count} book(s) successfully`,
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: "Error in API",
      error: error.message,
    });
  }
};

const myOrder = async (req, res) => {
  try {
    const userId = req.body.userId;

    const books = await bookModel.find({
      userId,
      $or: [{ sold: { $gt: 0 } }, { orders: { $gt: 0 } }],
    });

    res.status(200).json({ books });
  } catch (error) {
    res.status(500).json({ error: "Internal server error" });
  }
};

const currentOrder = async (req, res) => {
  try {
    const userId = req.body.userId;

    const books = await bookModel.find({
      userId,
      orders: { $gt: 0 }, // Matches documents where 'orders' field is greater than 0
    });

    res.status(200).json({ books });
  } catch (error) {
    res.status(500).json({ error: "Internal server error" });
  }
};

const completeOrder = async (req, res) => {
  try {
    const userId = req.body.userId;

    const books = await bookModel.find({
      userId,
      $or: [{ sold: { $gt: 0 } }, { approved: { $gt: 0 } }], // Matches documents where 'sold' field is greater than 0
    });

    res.status(200).json({ books });
  } catch (error) {
    res.status(500).json({ error: "Internal server error" });
  }
};

const weeklyStatOrderPrice = async (req, res) => {
  try {
    const userId = req.body.userId;
    const currentDate = new Date();
    const endOfCurrentWeek = currentDate;
    const daysOfCurrentWeek = eachDayOfInterval({
      start: subDays(currentDate, 6), // Set start date to 7 days before the current date
      end: endOfCurrentWeek,
    });
    console.log(daysOfCurrentWeek);
    const getTotalPriceSum = async (filter) => {
      const orders = await orderModel
        .find(filter)
        .populate({ path: "items.bookId" });
      sum = 0;
      orders.map((order) => {
        order.items.map((book) => {
          if (book.bookId.userId == userId) {
            sum += book.bookId.price * book.quantity;
          }
        });
      });
      return sum;
    };
    const getTotalCount = async (filter) => {
      const orders = await orderModel
        .find(filter)
        .populate({ path: "items.bookId" });
      count = 0;
      orders.map((order) => {
        order.items.map((book) => {
          if (book.bookId.userId == userId) {
            count += book.quantity;
          }
        });
      });
      return count;
    };

    const getOrderSumsForDay = async (day) => {
      const nextDay = new Date(day);
      nextDay.setDate(nextDay.getDate() + 1);

      const formattedDate = day.toISOString().split("T")[0];

      const createdTotalPriceSum = await getTotalPriceSum({
        createdAt: { $gte: day, $lt: nextDay },
      });
      const createdTotalCount = await getTotalCount({
        createdAt: { $gte: day, $lt: nextDay },
      });

      const deliveredTotalPriceSum = await getTotalPriceSum({
        status: "Delivered",
        updatedAt: { $gte: day, $lt: nextDay },
      });
      const deliveredTotalCount = await getTotalCount({
        status: "Delivered",
        updatedAt: { $gte: day, $lt: nextDay },
      });

      return {
        date: formattedDate,
        createdTotalPriceSum,
        deliveredTotalPriceSum,
        createdTotalCount,
        deliveredTotalCount,
      };
    };

    const daysArray = [];
    const orderSumsArrayD = [];
    const orderCountD = [];
    const orderSumsArrayC = [];
    const orderCountC = [];

    for (const day of daysOfCurrentWeek) {
      const orderSumsForDay = await getOrderSumsForDay(day);
      daysArray.push(orderSumsForDay.date);
      orderSumsArrayC.push(orderSumsForDay.createdTotalPriceSum);
      orderCountC.push(orderSumsForDay.createdTotalCount);
      orderSumsArrayD.push(orderSumsForDay.deliveredTotalPriceSum);
      orderCountD.push(orderSumsForDay.deliveredTotalCount);
    }

    res.status(200).json({
      days: daysArray,
      createdP: orderSumsArrayC,
      deliveredP: orderSumsArrayD,
      createdC: orderCountC,
      deliveredC: orderCountD,
    });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: "Internal server error" });
  }
};

const weeklyStatBooks = async (req, res) => {
  try {
    const userId = req.body.userId;
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
        if (item.bookId.userId == userId) {
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

module.exports = {
  sell,
  myOrder,
  currentOrder,
  completeOrder,
  weeklyStatBooks,
  weeklyStatOrderPrice,
};
