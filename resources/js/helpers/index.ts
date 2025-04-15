export const formatDate = (date: string | Date): string => {
    return new Date(date).toLocaleDateString();
};

export const formatTime = (date: string | Date): string => {
    return new Date(date).toLocaleTimeString();
};

export const formatDateTime = (date: string | Date): string => {
    return new Date(date).toLocaleString();
};

export const capitalizeFirstLetter = (str: string): string => {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
};