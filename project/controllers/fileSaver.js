const fs = require('fs');
const path = require('path');

function saveFile(file, fileName) {
  const uploadDirectory = path.join(__dirname, 'uploads');

  if (!fs.existsSync(uploadDirectory)) {
    fs.mkdirSync(uploadDirectory);
  }

  const filePath = path.join(uploadDirectory, fileName);

  const fileStream = fs.createWriteStream(filePath);
  console.log(file)
  fileStream.write(file.buffer);
  fileStream.end();

  console.log(`File ${fileName} saved at ${filePath}`);
}

module.exports = { saveFile };
