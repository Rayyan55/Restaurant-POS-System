<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4">🍔 Fast Food Admin Dashboard</h2>

    <ul class="nav nav-tabs mb-3" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="menu-tab" data-bs-toggle="tab" data-bs-target="#menu" type="button" role="tab">Menu Items</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab">Inventory</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="recipes-tab" data-bs-toggle="tab" data-bs-target="#recipes" type="button" role="tab">Recipes</button>
        </li>
    </ul>

    <div class="tab-content">
        <!-- MENU ITEMS -->
        <div class="tab-pane fade show active" id="menu" role="tabpanel">
            <h4>Add Menu Item</h4>
            <form id="menuForm" class="row g-3 mb-4">
                <div class="col-md-6">
                    <input class="form-control" type="text" id="menuName" placeholder="Item Name" required>
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="number" step="0.01" id="menuPrice" placeholder="Price" required>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success" type="submit">Add Item</button>
                </div>
            </form>
            <ul id="menuList" class="list-group"></ul>
        </div>

        <!-- INVENTORY -->
        <div class="tab-pane fade" id="inventory" role="tabpanel">
            <h4>Add Ingredient</h4>
            <form id="inventoryForm" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input class="form-control" type="text" id="invName" placeholder="Name" required>
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="number" id="invQty" placeholder="Quantity" required>
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="text" id="invUnit" value="pcs" placeholder="Unit" required>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" type="submit">Add</button>
                </div>
            </form>
            <ul id="inventoryList" class="list-group"></ul>
        </div>

        <!-- RECIPES -->
        <div class="tab-pane fade" id="recipes" role="tabpanel">
            <h4>Assign Ingredients to Menu Item</h4>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <select id="menuSelect" class="form-select"></select>
                </div>
            </div>

            <form id="recipeForm" class="row g-3 mb-4">
                <div class="col-md-6">
                    <select id="ingredientSelect" class="form-select"></select>
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="number" id="ingredientAmount" placeholder="Amount" required>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary" type="submit">Add to Recipe</button>
                </div>
            </form>
            <ul id="recipeList" class="list-group"></ul>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // === MENU SECTION ===
    const menuList = document.getElementById("menuList");
    const menuSelect = document.getElementById("menuSelect");

    document.getElementById("menuForm").addEventListener("submit", e => {
        e.preventDefault();
        fetch("api/add_menu_item.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                name: document.getElementById("menuName").value,
                price: document.getElementById("menuPrice").value
            })
        }).then(() => {
            document.getElementById("menuForm").reset();
            loadMenu();
        });
    });

    function loadMenu() {
        fetch("api/get_menu.php")
            .then(res => res.json())
            .then(data => {
                menuList.innerHTML = "";
                menuSelect.innerHTML = "";
                data.forEach(m => {
                    const li = document.createElement("li");
                    li.className = "list-group-item";
                    li.textContent = `${m.name} - $${m.price}`;
                    menuList.appendChild(li);

                    const opt = document.createElement("option");
                    opt.value = m.id;
                    opt.textContent = m.name;
                    menuSelect.appendChild(opt);
                });
                loadRecipe();
            });
    }

    // === INVENTORY SECTION ===
    const inventoryList = document.getElementById("inventoryList");
    const ingredientSelect = document.getElementById("ingredientSelect");

    document.getElementById("inventoryForm").addEventListener("submit", e => {
        e.preventDefault();
        fetch("api/add_inventory.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                name: document.getElementById("invName").value,
                quantity: document.getElementById("invQty").value,
                unit: document.getElementById("invUnit").value
            })
        }).then(() => {
            document.getElementById("inventoryForm").reset();
            loadInventory();
        });
    });

    function loadInventory() {
        fetch("api/get_inventory.php")
            .then(res => res.json())
            .then(data => {
                inventoryList.innerHTML = "";
                ingredientSelect.innerHTML = "";
                data.forEach(i => {
                    const li = document.createElement("li");
                    li.className = "list-group-item";
                    li.textContent = `${i.name} — ${i.quantity} ${i.unit}`;
                    inventoryList.appendChild(li);

                    const opt = document.createElement("option");
                    opt.value = i.id;
                    opt.textContent = `${i.name} (${i.unit})`;
                    ingredientSelect.appendChild(opt);
                });
            });
    }

    // === RECIPES SECTION ===
    const recipeList = document.getElementById("recipeList");

    document.getElementById("recipeForm").addEventListener("submit", e => {
        e.preventDefault();
        const menu_id = menuSelect.value;
        const ingredient_id = ingredientSelect.value;
        const amount = document.getElementById("ingredientAmount").value;

        fetch("api/add_recipe.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ menu_id, ingredient_id, amount })
        }).then(() => {
            document.getElementById("ingredientAmount").value = "";
            loadRecipe();
        });
    });

    function loadRecipe() {
        const menuId = menuSelect.value;
        if (!menuId) return;

        fetch("api/get_recipes.php?menu_id=" + menuId)
            .then(res => res.json())
            .then(data => {
                recipeList.innerHTML = "";
                data.forEach(r => {
                    const li = document.createElement("li");
                    li.className = "list-group-item";
                    li.textContent = `${r.ingredient} — ${r.amount} ${r.unit}`;
                    recipeList.appendChild(li);
                });
            });
    }

    menuSelect.addEventListener("change", loadRecipe);

    loadMenu();
    loadInventory();
</script>

</body>
</html>
