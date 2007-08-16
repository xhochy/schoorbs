var testcase = 0;

function init() {
    // get the endpoint
    schoorbsREST.endPoint = prompt('The HTTP-location of your Schoorbs installation', 
        'http://schoorbs.hsg-kl.de', 'Schoorbs-JS-REST-Client library TEST');
    nextTest();
}

function test() {
    switch(testcase) {
    case 1:
        if (schoorbsREST.login()) {
            testSucceded(1);
            nextTest();    
        } else {
            testFailed(1);
            alert('Proceeding impossible: login failed!');    
        }
        break;
    case 2: 
        alert('All tests finished');
        break;
    }
}

function nextTest() {
        testcase++;
        setTimeout('test()', 500);
}

function testSucceded(num) {
    document.getElementById('test-result-' + num.toString()).setAttribute('class', 'succeded');
    document.getElementById('test-result-' + num.toString()).setAttribute('value', 'succeded');
}

function testFailed(num) {
    document.getElementById('test-result-' + num.toString()).setAttribute('class', 'failed');
    document.getElementById('test-result-' + num.toString()).setAttribute('value', 'failed');
}