

// Afficher les alertes
function showAlert(message, type) {
	const alert = document.getElementById('alert');
	if (alert) {
		alert.textContent = message;
		alert.className = `alert show alert-${type}`;
		
		setTimeout(() => {
			alert.classList.remove('show');
		}, 4000);
	}
}


function openAddModal() {
	const modal = document.getElementById('addModal');
	if (modal) {
		modal.classList.add('show');
		const form = document.getElementById('addForm');
		if (form) form.reset();
	}
}

function closeAddModal() {
	const modal = document.getElementById('addModal');
	if (modal) modal.classList.remove('show');
}

function openEditModal(productId, name, description) {
	const productIdInput = document.getElementById('editProductId');
	const nameInput = document.getElementById('editName');
	const descInput = document.getElementById('editDescription');
	const modal = document.getElementById('editModal');
	
	if (productIdInput && nameInput && descInput && modal) {
		productIdInput.value = productId;
		nameInput.value = name;
		descInput.value = description;
		modal.classList.add('show');
	}
}

function closeEditModal() {
	const modal = document.getElementById('editModal');
	if (modal) modal.classList.remove('show');
}


async function deleteProduct(productId) {
	if (!confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
		return;
	}
	
	const formData = new FormData();
	formData.append('action', 'delete');
	formData.append('product_id', productId);
	
	try {
		const response = await fetch('actions/product_operations.php', {
			method: 'POST',
			body: formData
		});
		
		const text = await response.text();
		console.log('Delete response:', text);
		
		const data = JSON.parse(text);
		
		if (data.success) {
			showAlert(data.message, 'success');
			// Retirer la carte du produit avec animation
			const card = document.querySelector(`[data-product-id="${productId}"]`);
			if (card) {
				card.style.opacity = '0';
				card.style.transform = 'scale(0.8)';
				setTimeout(() => {
					card.remove();
					// Si aucun produit, recharger la page pour afficher le message vide
					if (document.querySelectorAll('.product-card').length === 0) {
						location.reload();
					}
				}, 300);
			}
		} else {
			showAlert(data.message, 'error');
		}
	} catch (error) {
		console.error('Error:', error);
		showAlert('Erreur: ' + error.message, 'error');
	}
}


window.onclick = function(event) {
	const addModal = document.getElementById('addModal');
	const editModal = document.getElementById('editModal');
	if (addModal && event.target === addModal) {
		closeAddModal();
	}
	if (editModal && event.target === editModal) {
		closeEditModal();
	}
};


if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initializeEventListeners);
} else {
	initializeEventListeners();
}

function initializeEventListeners() {
	console.log('Initializing event listeners');
	
	// Ajouter un produit
	const addForm = document.getElementById('addForm');
	if (addForm) {
		addForm.addEventListener('submit', async function(e) {
			e.preventDefault();
			console.log('Submit addForm');
			
			const formData = new FormData(this);
			
			try {
				const response = await fetch('actions/product_operations.php', {
					method: 'POST',
					body: formData
				});
				
				const text = await response.text();
				console.log('Add response:', text);
				
				const data = JSON.parse(text);
				
				if (data.success) {
					showAlert(data.message, 'success');
					closeAddModal();
					setTimeout(() => {
						location.reload();
					}, 500);
				} else {
					showAlert(data.message, 'error');
				}
			} catch (error) {
				console.error('Error:', error);
				showAlert('Erreur: ' + error.message, 'error');
			}
		});
	} else {
		console.warn('addForm not found');
	}
	
	// Éditer un produit
	const editForm = document.getElementById('editForm');
	if (editForm) {
		editForm.addEventListener('submit', async function(e) {
			e.preventDefault();
			console.log('Submit editForm');
			
			const formData = new FormData(this);
			
			try {
				const response = await fetch('actions/product_operations.php', {
					method: 'POST',
					body: formData
				});
				
				const text = await response.text();
				console.log('Edit response:', text);
				
				const data = JSON.parse(text);
				
				if (data.success) {
					showAlert(data.message, 'success');
					closeEditModal();
					setTimeout(() => {
						location.reload();
					}, 500);
				} else {
					showAlert(data.message, 'error');
				}
			} catch (error) {
				console.error('Error:', error);
				showAlert('Erreur: ' + error.message, 'error');
			}
		});
	} else {
		console.warn('editForm not found');
	}
}

