import {reactive} from "vue";

const liveChatStore = reactive({
    isLiveChatOpen: false,
    messages: [],
    toggleLiveChat() {
        this.isLiveChatOpen = !this.isLiveChatOpen;
         if (this.isLiveChatOpen) {
            // Save the current scroll position
            this.scrollPosition = window.scrollY;

            // Apply styles to lock the scroll
            document.body.style.position = 'fixed';
            document.body.style.top = `-${this.scrollPosition}px`;
            document.body.style.width = '100%';
        } else {
            // Restore body scroll
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.width = '';

            // Restore the previous scroll position
            window.scrollTo(0, this.scrollPosition);
        }
    },
    appendMessages(_messages) {
        this.messages = _messages.reverse().concat(this.messages);
    },
    appendMessage(_message) {
        if (this.messages.find(message => message.uuid === _message.uuid)) {
            return;
        }

        this.messages.push(_message);
    }
});

export default liveChatStore;
