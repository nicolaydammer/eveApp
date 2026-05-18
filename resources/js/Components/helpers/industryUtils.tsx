export const formatNum = (num) => new Intl.NumberFormat().format(num || 0);

export const formatISK = (num) => {
    if (num >= 1_000_000_000) return `${(num / 1_000_000_000).toFixed(2)} B`;
    if (num >= 1_000_000) return `${(num / 1_000_000).toFixed(1)} M`;
    return new Intl.NumberFormat(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
};

export const formatDuration = (totalSeconds) => {
    if (totalSeconds <= 0) return '0s';
    const days = Math.floor(totalSeconds / 86400);
    const hours = Math.floor((totalSeconds % 86400) / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = Math.floor(totalSeconds % 60);

    let result = [];
    if (days > 0) result.push(`${days}d`);
    if (hours > 0) result.push(`${hours}h`);
    if (minutes > 0) result.push(`${minutes}m`);
    if (seconds > 0 || result.length === 0) result.push(`${seconds}s`);
    return result.join(' ');
};

export const getScaledQuantity = (baseQty, batchMultiplier, materialModifier) => {
    if (!baseQty) return 0;
    return Math.max(batchMultiplier, Math.ceil(baseQty * batchMultiplier * (1 - materialModifier)));
};