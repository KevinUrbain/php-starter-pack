/**
 * =====================================================
 * JAVASCRIPT PRINCIPAL
 * =====================================================
 */

// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function() {
    
    // =====================================================
    // AUTO-FERMETURE DES ALERTES
    // =====================================================
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(function(alert) {
        // Ajouter un bouton de fermeture
        const closeBtn = document.createElement('span');
        closeBtn.innerHTML = '&times;';
        closeBtn.style.cssText = 'float: right; cursor: pointer; font-size: 1.5rem; font-weight: bold; margin-left: 1rem;';
        alert.insertBefore(closeBtn, alert.firstChild);
        
        // Événement de fermeture
        closeBtn.addEventListener('click', function() {
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 300);
        });
        
        // Auto-fermeture après 5 secondes
        setTimeout(function() {
            alert.style.transition = 'opacity 0.3s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 300);
        }, 5000);
    });
    
    // =====================================================
    // VALIDATION DE FORMULAIRE CÔTÉ CLIENT
    // =====================================================
    const forms = document.querySelectorAll('form');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Valider les champs requis
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'red';
                } else {
                    field.style.borderColor = '';
                }
            });
            
            // Valider les emails
            const emailFields = form.querySelectorAll('input[type="email"]');
            
            emailFields.forEach(function(field) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (field.value && !emailPattern.test(field.value)) {
                    isValid = false;
                    field.style.borderColor = 'red';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires correctement.');
            }
        });
    });
    
    // =====================================================
    // CONFIRMATION AVANT SUPPRESSION
    // =====================================================
    const deleteLinks = document.querySelectorAll('[data-confirm]');
    
    deleteLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || 'Êtes-vous sûr ?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
    
    // =====================================================
    // SMOOTH SCROLL
    // =====================================================
    const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
    
    smoothScrollLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId !== '#') {
                e.preventDefault();
                const target = document.querySelector(targetId);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
    
    // =====================================================
    // MENU MOBILE (si nécessaire)
    // =====================================================
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');
    
    if (mobileMenuBtn && navLinks) {
        mobileMenuBtn.addEventListener('click', function() {
            navLinks.classList.toggle('active');
        });
    }
    
    console.log('Application PHP Starter Pack initialisée ✓');
});
