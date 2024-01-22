const userModel = require("../models/userModel");
const bookModel = require("../models/bookModel");
const genreModel = require("../models/genreModel");
const JWT = require("jsonwebtoken");
const orderModel = require("../models/orderModel");

const searchController = async (req, res) => {
  const { name, condition } = req.body;
  const filter = {
    both: ["new", "old"],
    new: "new",
    old: "old",
  };
  if (name) {
    books = await bookModel
      .find({
        quantity: { $gt: 0 },
        condition: {
          $in: filter[condition],
        },
      })
      .populate("genre")
      .populate("userId");
  } else {
    books = await bookModel
      .find({
        name: new RegExp(req.body.name, "i"),
        quantity: { $gt: 0 },
        condition: {
          $in: filter[condition],
        },
      })
      .populate("genre")
      .populate("userId");
  }

  // console.log(books)
  return res.status(200).send({
    success: true,
    books,
  });
};
// stock for vendor
const stockController = async (req, res) => {
  console.log(req.body);
  const userId = req.body.userId;
  if (!req.body.name) {
    books = await bookModel.find({ userId: userId });
  } else {
    books = await bookModel.find({
      name: new RegExp(req.body.name, "i"),
      userId: userId,
    });
  }

  // console.log(books)
  return res.status(200).send({
    success: true,
    books,
  });
};

const addController = async (req, res) => {
  try {
    const token = req.headers["authorization"].split(" ")[1];
    JWT.verify(token, process.env.JWT_SECRET, (err, decode) => {
      req.body.userId = decode.userId;
    });

    // console.log(req.file.path)

    const existingUser = await userModel.findOne({ _id: req.body.userId }); //req.body alla data pass through the req
    // validation
    if (!existingUser) {
      return res.status(200).send({
        success: false,
        message: "User not exists",
      });
    }
    req.body.cover = req.file.path;
    req.body.cover = req.body.cover.replace("uploads\\", "");
    const book = new bookModel(req.body);
    await book.save();
    return res.status(201).send({
      success: true,
      message: "Book successfully added.",
    });
  } catch (error) {
    console.log(error);
    console.log(req.body);
    res.status(500).send({
      success: false,
      message: "Error in Register API",
      error,
    });
  }
};

const genreControler = async (req, res) => {
  try {
    const genres = await genreModel.find({}, { name: 1, _id: 1 });
    // const genres = [
    //     { name: "Action and Adventure" },
    //     { name: "Art and Photography" },
    //     { name: "Biographies and Autobiographies" },
    //     { name: "Business and Finance" },
    //     { name: "Children's" },
    //     { name: "Comics and Graphic Novels" },
    //     { name: "Cookbooks" },
    //     { name: "Crime and Mystery" },
    //     { name: "Dystopian" },
    //     { name: "Fantasy" },
    //     { name: "Historical Fiction" },
    //     { name: "Horror" },
    //     { name: "Humor and Comedy" },
    //     { name: "Literary Fiction" },
    //     { name: "Romance" },
    //     { name: "Science Fiction" },
    //     { name: "Self-Help and Personal Development" },
    //     { name: "Travel" },
    //     { name: "Thriller and Suspense" },
    //     { name: "Young Adult (YA)" }
    // ];
    // const genre = genreModel.insertMany(genres)
    console.log(genres);
    return res.status(200).send({
      success: true,
      genres,
    });
  } catch (error) {
    res.status(500).send({
      success: false,
      message: "Error in API",
      error,
    });
  }
};

const getBook = async (req, res) => {
  try {
    const bookId = req.body.bookId;

    const book = await bookModel
      .findById(bookId)
      .populate("genre")
      .populate("userId")
      .populate("reviews.userId");

    if (!book) {
      return res.status(404).json({ message: "Book not found" });
    }
    // Calculate sum of ratings and total count of reviews
    let sumOfRatings = 0;
    let totalCountOfReviews = 0;
    book.reviews.forEach((review) => {
      sumOfRatings += review.rating;
      totalCountOfReviews++;
    });
    const calculatedRating =
      totalCountOfReviews > 0 ? sumOfRatings / totalCountOfReviews : 0;

    const bookWithRaiting = {
      ...book.toObject(),
      rating: calculatedRating,
    };

    res.status(200).json({ book: bookWithRaiting });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: "Internal server error" });
  }
};

const getBookandReview = async (req, res) => {
  try {
    const { bookId, userId } = req.body;

    // Find the book based on the provided bookId and populate its associated reviews
    const book = await bookModel
      .findById(bookId)
      .populate("genre")
      .populate("userId")
      .populate("reviews.userId");

    if (!book) {
      return res.status(404).json({ message: "Book not found" });
    }
    // Calculate sum of ratings and total count of reviews
    let sumOfRatings = 0;
    let totalCountOfReviews = 0;
    book.reviews.forEach((review) => {
      sumOfRatings += review.rating;
      totalCountOfReviews++;
    });
    const calculatedRating =
      totalCountOfReviews > 0 ? sumOfRatings / totalCountOfReviews : 0;

    const userOrderedDelivered = await orderModel.findOne({
      user: userId,
      items: { $elemMatch: { bookId: bookId } },
      status: "Delivered",
    });

    // Determine if the user can review the book based on the delivered order
    const canReview = !!userOrderedDelivered;

    // Add 'canReview' field to the book object in the response
    const bookWithCanReview = {
      ...book.toObject(),
      canReview,
      rating: calculatedRating,
    };

    res.status(200).json({ book: bookWithCanReview });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: "Internal server error" });
  }
};

const postReview = async (req, res) => {
  try {
    const { userId, USERID, rating, comment, bookId } = req.body;
    console.log(req.body);
    const review = {
      userId,
      rating,
      comment,
    };

    const updatedBook = await bookModel.findByIdAndUpdate(
      bookId,
      { $push: { reviews: review } },
      { new: true }
    );

    if (!updatedBook) {
      return res.status(404).json({ message: "Book not found" });
    }
    res.status(200).json({ message: "Review added successfully", review });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: "Internal server error" });
  }
};

const editBook = async (req, res) => {
  try {
    let update = Object.assign({}, req.body);
    delete update.userId;
    delete update.bookId;
    console.log(req.body);
    const book = await bookModel.findById(req.body.bookId);
    if (book.userId == req.body.userId) {
      updatedBook = await bookModel.updateOne({ _id: req.body.bookId }, update);
    } else {
      return res.status(200).send({
        success: false,
        message: "Unauthorized",
      });
    }
    // const user = await userModel.updateOne({ _id: req.body.userId }, update);
    if (!updatedBook) {
      return res.status(401).send({
        success: false,
        message: "Something went wrong",
      });
    }
    return res.status(200).send({
      success: true,
      message: "Update Successful",
    });
  } catch (error) {
    console.log(error);
    res.status(500).send({
      success: false,
      message: "Error in Login Api",
      error,
    });
  }
};
module.exports = {
  postReview,
  getBookandReview,
  getBook,
  addController,
  genreControler,
  stockController,
  searchController,
  editBook,
};
