import {usePage} from "@inertiajs/vue3";

const formatDate = (dateString) => {
    const date = new Date(dateString);
    const options = { weekday: 'long', day: 'numeric' };

    return new Intl.DateTimeFormat([usePage().props.locale, 'en-US'], options).format(date);
};

function formatDateRange(range) {
    return `${formatDate(range[0])} - ${formatDate(range[1])}`
}

const formatToDDMM = (dateString) => {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');

    return `${day}/${month}`;
};

export {formatDate, formatDateRange, formatToDDMM};
