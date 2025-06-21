const API_URL = "./api.php";

window.onload = () => {
    fetchTareas();
};

function fetchTareas() {
    fetch(API_URL)
        .then(res => res.json())
        .then(data => {
            const lista = document.getElementById("listaTareas");
            lista.innerHTML = "";

            if (data.length === 0) {
                lista.innerHTML = "<li>No hay tareas registradas.</li>";
                return;
            }

            data.forEach(t => {
                const li = document.createElement("li");
                li.innerHTML = `
                    <label>
                        <input type="checkbox" ${t.completado ? 'checked' : ''} onchange="marcar(${t.id}, this.checked)">
                        <span class="${t.completado ? 'completada' : ''}">${t.titulo}</span>
                    </label>
                    <button onclick="eliminar(${t.id})">🗑</button>
                `;
                lista.appendChild(li);
            });
        })
        .catch(err => console.error("Error al cargar tareas:", err));
}

function agregarTarea() {
    const input = document.getElementById("nuevaTarea");
    const titulo = input.value.trim();
    if (titulo === "") return;

    fetch(API_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ titulo })
    }).then(() => {
        input.value = "";
        fetchTareas();
    }).catch(err => console.error("Error al agregar tarea:", err));
}

function marcar(id, completado) {
    fetch(API_URL, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id, completado })
    }).then(fetchTareas)
      .catch(err => console.error("Error al actualizar tarea:", err));
}

function eliminar(id) {
    fetch(API_URL, {
        method: "DELETE",
        body: `id=${id}`
    }).then(fetchTareas)
      .catch(err => console.error("Error al eliminar tarea:", err));
}
