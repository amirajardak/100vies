// Chatbot functionality
class BloodDonationChatbot {
    constructor() {
        this.isOpen = false;
        this.isMinimized = false;
        this.messages = [
            {
                id: '1',
                content: 'Bienvenue ! Je suis votre assistant 100Vies. Comment puis-je vous aider avec le don de sang aujourd\'hui ?',
                sender: 'bot',
                timestamp: new Date()
            }
        ];
        this.suggestions = [
            'Comment faire un don de sang ?',
            'Où trouver un centre de don ?',
            'Suis-je éligible pour donner ?',
            'Voir les campagnes à venir',
            'Comment créer un compte ?'
        ];
        this.init();
    }

    init() {
        this.createChatbotHTML();
        this.attachEventListeners();
        this.renderMessages();
        this.renderSuggestions();
    }

    createChatbotHTML() {
        const chatbotHTML = `
            <div id="chatbot-container" class="chatbot-container">
                <!-- Chat Window -->
                <div id="chat-window" class="chat-window" style="display: none;">
                    <!-- Header -->
                    <div class="chat-header">
                        <div class="chat-header-info">
                            <div class="chat-avatar">
                                <span>🩸</span>
                            </div>
                            <h3>Assistant 100Vies</h3>
                        </div>
                        <div class="chat-header-actions">
                            <button id="minimize-btn" class="chat-btn" title="Réduire">
                                <span id="minimize-icon">−</span>
                            </button>
                            <button id="close-chat-btn" class="chat-btn" title="Fermer">✕</button>
                        </div>
                    </div>

                    <!-- Messages Container -->
                    <div id="chat-messages" class="chat-messages">
                        <!-- Messages will be inserted here -->
                    </div>

                    <!-- Suggestions -->
                    <div id="chat-suggestions" class="chat-suggestions">
                        <!-- Suggestions will be inserted here -->
                    </div>

                    <!-- Input Area -->
                    <div class="chat-input-area">
                        <input 
                            type="text" 
                            id="chat-input" 
                            placeholder="Posez votre question..." 
                            autocomplete="off"
                        />
                        <button id="send-btn" class="send-btn">
                            <span>➤</span>
                        </button>
                    </div>
                </div>

                <!-- Chat Bubble Button -->
                <button id="chat-bubble-btn" class="chat-bubble-btn">
                    <span class="bubble-icon">💬</span>
                    <span class="bubble-pulse"></span>
                </button>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', chatbotHTML);
    }

    attachEventListeners() {
        const bubbleBtn = document.getElementById('chat-bubble-btn');
        const closeChatBtn = document.getElementById('close-chat-btn');
        const minimizeBtn = document.getElementById('minimize-btn');
        const sendBtn = document.getElementById('send-btn');
        const chatInput = document.getElementById('chat-input');

        bubbleBtn.addEventListener('click', () => this.toggleChat());
        closeChatBtn.addEventListener('click', () => this.toggleChat());
        minimizeBtn.addEventListener('click', () => this.toggleMinimize());
        sendBtn.addEventListener('click', () => this.sendMessage());
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.sendMessage();
        });
    }

    toggleChat() {
        this.isOpen = !this.isOpen;
        const chatWindow = document.getElementById('chat-window');
        const bubbleBtn = document.getElementById('chat-bubble-btn');
        
        if (this.isOpen) {
            chatWindow.style.display = 'flex';
            bubbleBtn.style.display = 'none';
            this.isMinimized = false;
            this.scrollToBottom();
        } else {
            chatWindow.style.display = 'none';
            bubbleBtn.style.display = 'flex';
        }
    }

    toggleMinimize() {
        this.isMinimized = !this.isMinimized;
        const chatMessages = document.getElementById('chat-messages');
        const chatSuggestions = document.getElementById('chat-suggestions');
        const chatInputArea = document.querySelector('.chat-input-area');
        const minimizeIcon = document.getElementById('minimize-icon');
        
        if (this.isMinimized) {
            chatMessages.style.display = 'none';
            chatSuggestions.style.display = 'none';
            chatInputArea.style.display = 'none';
            minimizeIcon.textContent = '□';
        } else {
            chatMessages.style.display = 'block';
            chatSuggestions.style.display = 'flex';
            chatInputArea.style.display = 'flex';
            minimizeIcon.textContent = '−';
            this.scrollToBottom();
        }
    }

    async sendMessage() {
        const input = document.getElementById('chat-input');
        const message = input.value.trim();
        
        if (!message) return;

        // Add user message
        this.addMessage(message, 'user');
        input.value = '';

        // Hide suggestions after first message
        document.getElementById('chat-suggestions').style.display = 'none';

        // Show typing indicator
        this.showTypingIndicator();

        // Get bot response from Mistral AI
        try {
            const response = await fetch("https://api.mistral.ai/v1/agents/completions", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer t2P7LNQs3ATqKsqV0W0zQKoUmNqifquR`,
                },
                body: JSON.stringify({
                    agent_id: "ag_019b9a1094ca77bea57301345abfd259",
                    messages: [
                        { 
                            role: "system", 
                            content: this.getSystemPrompt()
                        },
                        { 
                            role: "user", 
                            content: message 
                        }
                    ],
                }),
            });

            const data = await response.json();
            this.hideTypingIndicator();
            
            const botResponse = data.choices[0]?.message?.content || "Désolé, je n'ai pas pu comprendre. Pouvez-vous reformuler ?";
            this.addMessage(botResponse, 'bot');
        } catch (error) {
            console.error("Error fetching chatbot response:", error);
            this.hideTypingIndicator();
            this.addMessage("Oups ! Une erreur s'est produite. Veuillez réessayer.", 'bot');
        }
    }

    addMessage(content, sender) {
        const message = {
            id: Date.now().toString(),
            content: content,
            sender: sender,
            timestamp: new Date()
        };
        
        this.messages.push(message);
        this.renderMessages();
        this.scrollToBottom();
    }

    renderMessages() {
        const messagesContainer = document.getElementById('chat-messages');
        messagesContainer.innerHTML = this.messages.map(msg => `
            <div class="message ${msg.sender}-message">
                <div class="message-content">
                    <p>${msg.content}</p>
                    <span class="message-time">
                        ${msg.timestamp.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}
                    </span>
                </div>
            </div>
        `).join('');
    }

    renderSuggestions() {
        const suggestionsContainer = document.getElementById('chat-suggestions');
        suggestionsContainer.innerHTML = this.suggestions.map(suggestion => `
            <button class="suggestion-btn" data-suggestion="${suggestion}">
                ${suggestion}
            </button>
        `).join('');

        // Add click handlers to suggestion buttons
        document.querySelectorAll('.suggestion-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('chat-input').value = btn.dataset.suggestion;
                this.sendMessage();
            });
        });
    }

    showTypingIndicator() {
        const messagesContainer = document.getElementById('chat-messages');
        const typingHTML = `
            <div class="message bot-message typing-indicator" id="typing-indicator">
                <div class="message-content">
                    <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        `;
        messagesContainer.insertAdjacentHTML('beforeend', typingHTML);
        this.scrollToBottom();
    }

    hideTypingIndicator() {
        const indicator = document.getElementById('typing-indicator');
        if (indicator) indicator.remove();
    }

    scrollToBottom() {
        const messagesContainer = document.getElementById('chat-messages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    getSystemPrompt() {
        return `Tu es l'assistant virtuel de 100Vies, une plateforme tunisienne dédiée au don de sang. Ton rôle est d'aider les utilisateurs avec des informations précises sur le don de sang en Tunisie.

RÈGLES IMPORTANTES:
1. Réponds UNIQUEMENT aux questions sur le don de sang, les groupes sanguins, et la navigation sur le site 100Vies
2. Garde tes réponses COURTES et CLAIRES (maximum 3-4 phrases)
3. Concentre-toi sur le contexte TUNISIEN
4. Si la question n'est pas liée au don de sang, redirige poliment vers le sujet
5. Utilise du HTML pour les liens: <a href="URL">texte</a>

INFORMATIONS SUR LES GROUPES SANGUINS:
- O- : Donneur universel (peut donner à tous les groupes)
- AB+ : Receveur universel (peut recevoir de tous les groupes)
- O+ : Peut donner à tous les positifs (O+, A+, B+, AB+)
- A+ : Peut donner à A+ et AB+
- B+ : Peut donner à B+ et AB+
- A- : Peut donner à A+, A-, AB+, AB-
- B- : Peut donner à B+, B-, AB+, AB-
- AB- : Peut donner à AB+ et AB-

CRITÈRES D'ÉLIGIBILITÉ EN TUNISIE:
- Âge: 18 à 65 ans
- Poids minimum: 50 kg
- Bonne santé générale
- Fréquence: Tous les 3 mois pour le sang total
- Durée du don: 10 minutes pour le prélèvement, 45 minutes au total

STRUCTURE DU SITE 100VIES:
- Accueil: <a href="index1.php">index1.php</a> - Présentation de la plateforme
- Campagnes: <a href="campagnes-evenements.php">campagnes-evenements.php</a> - Événements de collecte
- Centres: <a href="centres.php">centres.php</a> - Liste des centres de don en Tunisie
- Éligibilité: <a href="eligibilite.php">eligibilite.php</a> - Test d'éligibilité
- Témoignages: <a href="temoignages.php">temoignages.php</a> - Histoires de donneurs/receveurs
- Appel urgent: <a href="appelurgent.php">appelurgent.php</a> - Publier un besoin urgent
- Contact: <a href="contact.php">contact.php</a> - Nous joindre
- Inscription: <a href="../../administrateur/php/inscription.php">inscription.php</a> - Créer un compte Donneur/Receveur
- Connexion: <a href="../../administrateur/php/form.php">form.php</a> - Se connecter
- À propos: <a href="a-propos.php">a-propos.php</a> - Notre mission
- Rendez-vous: <a href="rdv.php">rdv.php</a> - Réserver un don

CONSEILS AVANT LE DON:
- Bien se reposer la veille
- Boire beaucoup d'eau
- Manger normalement (éviter les repas trop gras)
- Apporter une pièce d'identité

APRÈS LE DON:
- Se reposer quelques minutes
- Boire beaucoup d'eau
- Éviter les efforts intenses pendant 24h
- Prendre la collation offerte

Format de réponse: Sois chaleureux, encourageant et informatif. Utilise des émojis 🩸❤️ avec modération. Fournis toujours des liens cliquables vers les pages concernées.`;
    }
}

// Initialize chatbot when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new BloodDonationChatbot();
});
