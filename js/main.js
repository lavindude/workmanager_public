var classTable = document.getElementById('ct');
var taskTable = document.getElementById('tt');
var eventTable = document.getElementById('et');
var linkTable = document.getElementById('lt');

if (typeof(classTable) != 'undefined' && classTable != null) {

    classTable.addEventListener('mouseover', function(event) {
        classTable.style.backgroundColor = "#faa605";
        classTable.style.borderRadius = "10px";
        classTable.style.fontWeight = "bold";
        classTable.style.textDecoration = "underline";
    });

    classTable.addEventListener('mouseout', function(event) {
        classTable.style.backgroundColor = "gold";
        classTable.style.borderRadius = "30px";
        classTable.style.fontWeight = "normal";
        classTable.style.textDecoration = "none";
    });
}

if (typeof(taskTable) != 'undefined' && taskTable != null) {

    taskTable.addEventListener('mouseover', function(event) {
        taskTable.style.backgroundColor = "#faa605";
        taskTable.style.borderRadius = "10px";
        taskTable.style.fontWeight = "bold";
        taskTable.style.textDecoration = "underline";
    });

    taskTable.addEventListener('mouseout', function(event) {
        taskTable.style.backgroundColor = "gold";
        taskTable.style.borderRadius = "30px";
        taskTable.style.fontWeight = "normal";
        taskTable.style.textDecoration = "none";
    });
}

if (typeof(eventTable) != 'undefined' && eventTable != null) {
    eventTable.addEventListener('mouseover', function(event) {
        eventTable.style.backgroundColor = "#faa605";
        eventTable.style.borderRadius = "10px";
        eventTable.style.fontWeight = "bold";
        eventTable.style.textDecoration = "underline";
    });

    eventTable.addEventListener('mouseout', function(event) {
        eventTable.style.backgroundColor = "gold";
        eventTable.style.borderRadius = "30px";
        eventTable.style.fontWeight = "normal";
        eventTable.style.textDecoration = "none";
    });
}

if (typeof(linkTable) != 'undefined' && linkTable != null) {
    linkTable.addEventListener('mouseover', function(event) {
        linkTable.style.backgroundColor = "#faa605";
        linkTable.style.borderRadius = "10px";
        linkTable.style.fontWeight = "bold";
        linkTable.style.textDecoration = "underline";
    });

    linkTable.addEventListener('mouseout', function(event) {
        linkTable.style.backgroundColor = "gold";
        linkTable.style.borderRadius = "30px";
        linkTable.style.fontWeight = "normal";
        linkTable.style.textDecoration = "none";
    });
}