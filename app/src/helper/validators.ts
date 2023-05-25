import {isNumber} from "chart.js/helpers";

export const isEmpty = (value: string): boolean => {
    return !value || 0 === value.trim().length;
};

export const isSelected = (value): boolean => {
    return null !== value;
};

export const isYearValid = (value): boolean => {
    return !isNaN(value);
};

export const isEmailValid = (value): boolean => {
    const regex = /^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/;

    return null === value.match(regex);
};

export const isDateTimeFormatValid = (value: string): boolean => {
    const regex = /[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (2[0-3]|[01][0-9]):[0-5][0-9]/;

    return null !== value.match(regex);
}
