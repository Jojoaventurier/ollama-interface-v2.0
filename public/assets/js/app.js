document.addEventListener("DOMContentLoaded", () => {
    const modelSelect = document.getElementById("modelSelect");
    const chatBox = document.getElementById("chatBox");
    const responseBox = document.getElementById("responseBox");
    console.log('hello');
  
    // Fetch and display available models
    fetch("/ollama-interface-v2.0/public/api/models.php")
      .then((res) => res.json())
      .then((models) => {
        modelSelect.innerHTML = models.map(
          (model) => `<option value="${model}">${model}</option>`
        ).join("");
      });
  
    // Handle chat form submission
    document.getElementById("chatForm").addEventListener("submit", (e) => {
      e.preventDefault();
      const model = modelSelect.value;
      const prompt = chatBox.value;
  
      fetch("/api/chat.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ model, prompt }),
      })
        .then((res) => res.json())
        .then((data) => {
          responseBox.innerText = data.response || "No response";
        });
    });

    // Save a conversation via POST request
    function saveConversation(model, prompt, response) {
        fetch("/index.php?route=save-conversation", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ model, prompt, response }),
        })
        .then((res) => res.json())
        .then((data) => {
            if (data.status === "success") {
            alert("Conversation saved!");
            } else {
            alert("Failed to save conversation: " + data.message);
            }
        });
    }

  });