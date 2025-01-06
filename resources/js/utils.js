export const formatDuration = (duration) => {
   if (!duration) {
        return '0:00';
    }

    let minutes = Math.floor(duration / 60);
    let seconds = Math.floor(duration % 60);

    seconds = seconds < 10 ? '0' + seconds : seconds;

    return minutes + ':' + seconds;
}
export const formatAsteriskString = (string, classes) => {
    return string.replace(/\*([\w\s]+)\*/g, `<span class="${classes}">$1</span>`);
};
