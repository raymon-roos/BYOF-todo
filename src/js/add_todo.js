function startWatchingForInput() {
    document.querySelector(".addTodo input:last-of-type").addEventListener("input", createNewTodoEntry);
    console.log(document.querySelectorAll(".addTodo input[type=text]:last-child "));
}

startWatchingForInput();

function createNewTodoEntry() {
    const newTodo = document.createElement("input").appendChild(document.createTextNode("This is new."));
    const element = document.querySelector(".addTodo");
    element.appendChild(newTodo);
}
