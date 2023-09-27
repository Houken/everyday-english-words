const convertEnglish = (word, testTypeIsRead, testHintLevel) => {
    if (!testTypeIsRead && testHintLevel === 2) {
        const firstChar = word.charAt(0);
        const remaining = word.slice(1);

        return firstChar + remaining.replace(/[a-zA-z]/g, ' _');

    } else if (!testTypeIsRead && testHintLevel === 1) {
        return word.replace(/[a-zA-z]/g, ' _');
    }  else if (!testTypeIsRead && testHintLevel === 0) {
        return '';
    } else {
        return word
    }
}

export default convertEnglish
