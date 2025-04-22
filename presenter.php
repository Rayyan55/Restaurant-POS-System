<!DOCTYPE html>
<html>
<head><title>Presenter Dashboard</title></head>
<body>
<h2>Ready Orders</h2>
<div id="readyOrders"></div>

<script>
    function loadReadyOrders() {
        fetch("api/get_orders.php?status=ready")
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById("readyOrders");
                container.innerHTML = "";

                data.forEach(order => {
                    const div = document.createElement("div");
                    div.innerHTML = `
              <p><strong>Order #${order.id} is Ready</strong></p>
              <p>Items: ${JSON.parse(order.items).join(", ")}</p>
              <hr>
            `;
                    container.appendChild(div);
                });
            });
    }

    setInterval(loadReadyOrders, 3000);
    loadReadyOrders();
</script>
</body>
</html>
