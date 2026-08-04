export default (text, speed = 45, pause = 2000) => ({
    display: "",

    async start() {
        while (true) {
            this.display = "";

            for (const char of text) {
                this.display += char === "|" ? "<br>" : char;
                await this.sleep(speed);
            }

            await this.sleep(pause);
        }
    },

    sleep(ms) {
        return new Promise((resolve) => setTimeout(resolve, ms));
    },
});
