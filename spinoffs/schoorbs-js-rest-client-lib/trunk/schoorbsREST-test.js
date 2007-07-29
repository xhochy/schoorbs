// cache all used variables
var input1 = null;
var failed = false;

function failure(message) {
    print('[FAILURE] ' + message);
    failed = true;
}

function succeded(func) {
    print('[SUCCEDED] ' + func);
}

print('======== STARTING UNITTEST FOR "schoorbsREST.js" =======');

// Load the file that will be tested
load('schoorbsREST.js');

// Get all needed configuration settings
print('Please enter the URL to your Schoorbs installation');
schoorbsREST.endPoint = readline();
print('Please enter your Schoorbs-username');
schoorbsREST.user = readline();
print('Please enter your Schoorbs-password (Attention: Your input will be visible!)');
schoorbsREST.passwd = readline();

// Start the tests
print('Enter a valid Room');
input1 = readline();
if (schoorbsREST.getRoomID(input1) !== false) {
    succeded ('getRoomID');    
} else {
    failure('getRoomID');
}
if (failed) {
    print('******** UNITTEST FOR "schoorbsREST.js" FAILED ********');
} else {
    print('======== UNITTEST FOR "schoorbsREST.js" FINISHED SUCCESFULLY =======');    
}
