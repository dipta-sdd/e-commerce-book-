const userModel = require("../models/userModel");
const bookModel = require("../models/bookModel");
const orderModel = require("../models/orderModel");
const bcrypt = require("bcryptjs");
const jwt = require("jsonwebtoken");

const multer = require("multer");
const path = require("path");
const { saveFile } = require("./fileSaver");
const { generateAndSendOTP, verifyOTP } = require("./otpController");

const storage = multer.memoryStorage(); // Using memory storage for receiving the file

const upload = multer({ storage: storage });

// register controller
const registerController = async (req, res) => {
  try {
    const existingUser = await userModel.findOne({ email: req.body.email }); //req.body alla data pass through the req
    // validation
    if (existingUser) {
      return res.status(200).send({
        success: false,
        message: "User Already Exists",
      });
    }
    const salt = await bcrypt.genSalt(10); //hashing method
    const hashedPassword = await bcrypt.hash(req.body.password, salt);
    req.body.password = hashedPassword;
    // save data
    const user = new userModel(req.body);
    await user.save();
    generateAndSendOTP(user._id, user.email);
    return res.status(201).send({
      success: true,
      message:
        "An verification code has been sent to your email, plz check it.",
      userId: user._id,
    });
  } catch (error) {
    console.log(error);
    res.status(500).send({
      success: false,
      message: "Error in Register API",
      error,
    });
  }
};

const otpController = async (req, res) => {
  try {
    const { userId, code } = req.body;
    const otp = await verifyOTP(userId, code);
    if (!otp) {
      return res.status(200).send({
        success: false,
        message: "Verification code invalid or expired",
      });
    }
    const user = await userModel.findOne({ _id: userId });
    if (!user) {
      return res.status(200).send({
        success: false,
        message: "Invalid Credentials",
      });
    }
    user.status = "approved";
    await user.save();
    const token = jwt.sign({ userId: user._id }, process.env.JWT_SECRET, {
      expiresIn: "2d",
    });
    return res.status(200).send({
      success: true,
      message: "Login Successful",
      token,
      user,
    });
  } catch (error) {
    console.log(error);
    res.status(500).send({
      success: false,
      message: "Error in API",
      error,
    });
  }
};

const resendOTP = async (req, res) => {
  try {
    const { email } = req.body;
    console.log(req.body);
    // if (userId) {
    //   user = await userModel.findOne({ _id: userId });
    // } else {
    user = await userModel.findOne({ email: email });
    // }

    generateAndSendOTP(user._id, user.email);
    return res.status(201).send({
      success: true,
      message:
        "An verification code has been sent to your email, plz check it.",
      userId: user._id,
    });
  } catch (error) {
    console.log(error);
    res.status(500).send({
      success: false,
      message: "Error in API",
      error,
    });
  }
};

// login controller
const loginController = async (req, res) => {
  try {
    console.log(req.body);
    const user = await userModel.findOne({ email: req.body.email });
    if (!user) {
      return res.status(200).send({
        success: false,
        message: "Invalid Credentials",
      });
    }
    // checking password
    const comparePasseword = await bcrypt.compare(
      req.body.password,
      user.password
    );
    if (!comparePasseword) {
      return res.status(200).send({
        success: false,
        message: "Invalid Credentials",
      });
    }
    if (user.status == "pending") {
      return res.status(200).send({
        success: false,
        message: "otp",
        userId: user._id,
      });
    }
    const token = jwt.sign({ userId: user._id }, process.env.JWT_SECRET, {
      expiresIn: "2d",
    });
    return res.status(200).send({
      success: true,
      message: "Login Successful",
      token,
      user,
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

// current user controller
const currentUserController = async (req, res) => {
  try {
    const user = await userModel.findOne({ _id: req.body.userId }).lean();
    if (user.role == "user") {
      const sumOfQuantity = user.cart.books.reduce((acc, book) => {
        return acc + book.quantity;
      }, 0);
      // Add sumOfQuantity to user.cartCount
      user.cartCount = sumOfQuantity;
    }
    return res.status(200).send({
      success: true,
      message: "User fetched successfully",
      user,
    });
  } catch (error) {
    console.log(error);
    return res.status(500).send({
      success: false,
      message: "Unable to get current user",
      error,
    });
  }
};

const updateUser = async (req, res) => {
  try {
    let update = Object.assign({}, req.body);
    delete update.userId;
    console.log(req.body);
    const user = await userModel.updateOne({ _id: req.body.userId }, update);
    if (!user) {
      return res.status(401).send({
        success: false,
        message: "Something went wrong",
      });
    }
    return res.status(200).send({
      success: true,
      message: "Update Successful",
      user,
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

const uploader = async (req, res) => {
  try {
    return res.status(200).send({
      success: true,
      message: "Update Successful",
      user,
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

const receiveFile = async (req, res) => {
  return upload.single("file")(req, res, function (err) {
    // if (err instanceof multer.MulterError) {
    //   return res.status(400).send('Multer error');
    // } else if (err) {
    //   return res.status(500).send('Internal server error');
    // }

    console.log(req.file);
    console.log(req.body.file);
    if (!req.body.file) {
      return res.status(400).send("No image uploaded.");
    }

    // Pass the received file and its name to another function for saving or further processing
    saveFile(req.body.file, "hhhh.jpg");

    // Respond to the client
    res.status(200).send("File received successfully");
  });
};

const cartPush = async (req, res) => {
  const { bookId, quantity, userId } = req.body;

  // Find the user by their userId
  userModel
    .findById(userId)
    .then((user) => {
      if (user) {
        console.log(bookId);
        // Check if the book is already in the cart
        const foundBook = user.cart.books.find((book) => book.bookId == bookId);
        console.log(foundBook);
        if (foundBook) {
          // Book found in the cart, increment its quantity
          foundBook.quantity += quantity;
          if (foundBook.quantity <= 0) {
            user.cart.books.pull(foundBook);
            console.log("book remiovedS");
          }
        } else {
          // Book not found in the cart, add it as a new item
          console.log("new book ");
          console.log(quantity);
          user.cart.books.push({
            bookId: bookId,
            quantity: quantity,
          });
        }

        // Save the updated user object back to the database
        return user.save();
      } else {
        // User not found
        throw new Error("User not found");
      }
    })
    .then((updatedUser) => {
      // Updated user with the book added/incremented successfully
      // console.log('Updated user:', updatedUser);
      // Send a response indicating success, if needed
      // res.status(200).json({ message: 'Book added to cart or quantity incremented successfully' });
      // console.log(quantity);
      totalCart(res, userId, bookId);
    })
    .catch((error) => {
      // Handle errors, such as user not found or database error
      console.error("Error updating cart with book:", error);
      // Send an error response
      res.status(500).json({ error: "Could not add book to cart" });
    });
};

const cartGet = async (req, res) => {
  const { userId } = req.body;
  try {
    const user = await userModel
      .findOne({ _id: userId })
      .populate({ path: "cart.books.bookId", populate: { path: "genre" } });
    const cart = user.cart;
    // console.log(cart)
    return res.status(200).send({
      success: true,
      cart,
    });
  } catch (error) {
    console.log(error);
    res.status(500).send({
      success: false,
      message: "Error in Api",
      error,
    });
  }
};

const cartCount = async (req, res) => {
  totalCart(res, req.body.userId);
};

// toatl cart sum
function totalCart(res, userId, bookId) {
  userModel
    .findById(userId)
    .then((user) => {
      if (user) {
        // Calculate the sum of quantity in the user's book array
        const sumOfQuantity = user.cart.books.reduce((acc, book) => {
          return acc + book.quantity;
        }, 0);

        res.status(200).json({ cart: sumOfQuantity, bookId });
      } else {
        // User not found
        throw new Error("User not found");
      }
    })
    .catch((error) => {
      // Handle errors, such as user not found or database error
      console.error("Error finding sum of quantity:", error);
      // Send an error response
      res.status(500).json({ error: "Could not calculate sum of quantity" });
    });
}

const checkout = async (req, res) => {
  const { userId } = req.body;
  try {
    console.log(req.body);
    // const user =  await userModel.findOne({ _id : userId }).populate({path: 'cart.books.bookId',populate: { path: 'genre' }});
  } catch (error) {
    console.log(error);
    res.status(500).send({
      success: false,
      message: "Error in Api",
      error,
    });
  }
};

const passwordChange = async (req, res) => {
  try {
    const { userId, password, passwordNew } = req.body;
    const user = await userModel.findOne({ _id: userId });
    if (!user) {
      return res.status(401).send({
        success: false,
        message: "Invalid Credentials",
      });
    }
    const otp = await verifyOTP(userId, code);
    const comparePassword = await bcrypt.compare(password, user.password);
    if (!comparePassword) {
      return res.status(401).send({
        success: false,
        message: "Invalid Credentials",
      });
    }
    const hashedPassword = await bcrypt.hash(passwordNew, 10);
    user.password = hashedPassword;
    await user.save();
    res.status(200).send({
      success: true,
      message: "Password updated successfully",
    });
  } catch (error) {
    console.error(error);
    res.status(500).send({
      success: false,
      message: "Error in Password Change API",
      error,
    });
  }
};

const passwordReset = async (req, res) => {
  try {
    const { email, password, code } = req.body;
    const user = await userModel.findOne({ email: email });
    if (!user) {
      return res.status(200).send({
        success: false,
        message: "Invalid Email",
      });
    }
    const otp = await verifyOTP(user._id, code);
    if (!otp) {
      return res.status(200).send({
        success: false,
        message: "Verification code invalid or expired",
      });
    }
    const hashedPassword = await bcrypt.hash(password, 10);
    user.password = hashedPassword;
    await user.save();
    res.status(200).send({
      success: true,
      message: "Password updated successfully",
    });
  } catch (error) {
    console.error(error);
    res.status(500).send({
      success: false,
      message: "Error in Password Change API",
      error,
    });
  }
};

const userDetails = async (req, res) => {
  try {
    const { USERID } = req.body;

    // Find the user based on the USERID
    const user = await userModel.findById(USERID).populate("reviews.userId");
    if (!user) {
      return res.status(404).json({ message: "User not found" });
    }
    let totalRating = 0;
    let reviewCount = 0;

    user.reviews.forEach((review) => {
      totalRating += review.rating;
      reviewCount++;
    });
    const averageRating = reviewCount > 0 ? totalRating / reviewCount : 0;
    const bookCount = await bookModel.countDocuments({ userId: USERID });
    const userDetails = {
      user,
      averageRating: averageRating,
      bookCount: bookCount,
    };

    res.status(200).json(userDetails);
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: "Internal server error" });
  }
};

const canReview = async (req, res) => {
  try {
    const { userId, USERID } = req.body;
    const user = await userModel.findById(USERID).populate("reviews.userId");
    if (!user) {
      return res.status(404).json({ message: "User not found" });
    }
    let totalRating = 0;
    let reviewCount = 0;

    user.reviews.forEach((review) => {
      totalRating += review.rating;
      reviewCount++;
    });
    const averageRating = reviewCount > 0 ? totalRating / reviewCount : 0;
    const bookCount = await bookModel.countDocuments({ userId: USERID });

    const order = await orderModel.findOne({ user: userId }).populate({
      path: "items.bookId",
      match: { userId: USERID },
    });

    if (order && order.items.length > 0) {
      return res.status(200).json({
        user,
        canReview: true,
        averageRating: averageRating,
        bookCount: bookCount,
      });
    } else {
      return res.status(200).json({
        user,
        canReview: false,
        averageRating: averageRating,
        bookCount: bookCount,
      });
    }
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: "Internal server error" });
  }
};

const postReview = async (req, res) => {
  try {
    const { userId, USERID, rating, comment } = req.body;

    const review = {
      userId,
      rating,
      comment,
    };

    const user = await userModel.findByIdAndUpdate(
      USERID,
      { $push: { reviews: review } },
      { new: true }
    );

    if (!user) {
      return res.status(404).json({ message: "User not found" });
    }
    res.status(200).json({ message: "Review added successfully" });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: "Internal server error" });
  }
};

module.exports = {
  otpController,
  passwordChange,
  checkout,
  cartGet,
  cartCount,
  cartPush,
  registerController,
  loginController,
  currentUserController,
  updateUser,
  uploader,
  receiveFile,
  resendOTP,
  passwordReset,
  userDetails,
  canReview,
  postReview,
};
