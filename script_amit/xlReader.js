/*
  Script to read and print XLSX file content.
  Run the script with xlsx file as argument.
  e.g: node xlReader.js xlFile.xlsx
*/
const XLSX = require('xlsx');
const fs = require('fs');
let xlsxFile = process.argv.slice(2);
if(xlsxFile.length !== 1){
  console.error("Invalid amount of params");
  process.exit(1);
}
xlsxFile = xlsxFile[0];
if(!xlsxFile.endsWith(".xlsx")){
  console.error("File must ends with xlsx");
  process.exit(1);
}
if(!fileExists(xlsxFile)){
  console.error("File " + xlsxFile + " does not exist");
  process.exit(1);
}
// Read the content of the XLSX file
const workbook = XLSX.readFile(xlsxFile);

// Get the first sheet
const firstSheetName = workbook.SheetNames[0];
const worksheet = workbook.Sheets[firstSheetName];

// Convert the worksheet to an array of objects
const data = XLSX.utils.sheet_to_json(worksheet);

// Display the data
const jsonData = JSON.stringify(data);
console.log(jsonData);

/* Return true if the given file exist (expects full path of the file)*/
function fileExists(filePath) {
  try {
    fs.accessSync(filePath, fs.constants.F_OK);
    return true;
  } catch (error) {
    return false;
  }
}
