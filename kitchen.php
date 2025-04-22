<!DOCTYPE html>
<html>
<head><title>Kitchen Orders</title></head>
<body>
<h2>Kitchen Orders</h2>
<div id="orders"></div>

<script>
    function loadOrders() {
        fetch("api/get_orders.php?status=pending")
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById("orders");
                container.innerHTML = "";

                data.forEach(order => {
                    const div = document.createElement("div");
                    div.innerHTML = `
              <p><strong>Order #${order.id}</strong></p>
              <p>Items: ${JSON.parse(order.items).join(", ")}</p>
              <button onclick="markReady(${order.id})">Mark as Ready</button>
              <hr>
            `;
                    container.appendChild(div);
                });
            });
    }

    function markReady(order_id) {
        fetch("api/mark_ready.php", {
            method: "POST",
            body: JSON.stringify({ order_id }),
            headers: { "Content-Type": "application/json" }
        }).then(() => loadOrders());
    }

    setInterval(loadOrders, 3000);
    loadOrders();
</script>
</body>
</html>
