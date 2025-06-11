let cartItems = [];

function toggleCart() {
  const cartBox = document.getElementById('cartBox');
  cartBox.classList.toggle('d-none');
  cartBox.style.display = cartBox.classList.contains('d-none') ? 'none' : 'block';
  updateCart();
}

function addItem(button, name, price) {
  const quantityInput = button.parentElement.querySelector('.quantidade-produto');
  const quantity = parseInt(quantityInput?.value);

  if (!quantity || quantity <= 0) {
    alert('Por favor, insira uma quantidade válida.');
    return;
  }

  const existingIndex = cartItems.findIndex(item => item.name === name);
  if (existingIndex >= 0) {
    cartItems[existingIndex].quantity += quantity;
  } else {
    cartItems.push({ name, price, quantity });
  }

  updateCart();
}

function removeItem(index) {
  if (index >= 0 && index < cartItems.length) {
    cartItems.splice(index, 1);
    updateCart();
  }
}

function updateCart() {
  const cartList = document.getElementById('cartItems');
  const cartMessage = document.getElementById('cartMessage');
  const cartLabel = document.getElementById('cart-label');

  cartList.innerHTML = '';
  cartMessage.style.display = cartItems.length === 0 ? 'block' : 'none';

  const fragment = document.createDocumentFragment();
  let subtotal = 0;
  let totalQuantity = 0;

  cartItems.forEach((item, index) => {
    const itemTotal = item.price * item.quantity;
    subtotal += itemTotal;
    totalQuantity += item.quantity;

    const li = document.createElement('li');
    li.innerHTML = `
      ${item.name} - ${item.quantity}x R$ ${item.price.toFixed(2)} = <strong>R$ ${itemTotal.toFixed(2)}</strong>
      <button class="btn btn-sm btn-danger ms-2" onclick="removeItem(${index})">
        <i class="bi bi-trash"></i>
      </button>
    `;
    fragment.appendChild(li);
  });

  cartList.appendChild(fragment);
  document.getElementById('subtotal').textContent = `R$ ${subtotal.toFixed(2)}`;
  document.getElementById('total').textContent = `R$ ${subtotal.toFixed(2)}`;

  if (cartLabel) {
    cartLabel.textContent = totalQuantity === 0
      ? 'MEU CARRINHO (vazio)'
      : `MEU CARRINHO (${totalQuantity} itens)`;
  }
}

document.addEventListener('DOMContentLoaded', () => {
  updateCart();

  const checkoutBtn = document.querySelector('.checkout');
  if (checkoutBtn) {
    checkoutBtn.addEventListener('click', () => {
      if (cartItems.length === 0) {
        alert('Seu carrinho está vazio!');
        return;
      }

      let mensagem = 'Olá! Gostaria de finalizar a compra com os seguintes itens:';
      cartItems.forEach(item => {
        mensagem += `\n${item.name} - ${item.quantity}x R$ ${item.price.toFixed(2)} = R$ ${(item.price * item.quantity).toFixed(2)}`;
      });

      const total = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
      mensagem += `\nTotal: R$ ${total.toFixed(2)}`;

      const numero = '55991495642';
      window.open(`https://wa.me/${numero}?text=${encodeURIComponent(mensagem)}`, '_blank');
    });
  }
});