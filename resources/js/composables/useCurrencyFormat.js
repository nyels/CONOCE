/**
 * Composable para formato de moneda mexicana
 * Formato: $1,000.00 (comas como separador de miles, 2 decimales)
 *
 * @example
 * const { formatMoney, parseMoney, formatInput } = useCurrencyFormat();
 * formatMoney(1000.5) // "1,000.50"
 * parseMoney("1,000.50") // 1000.50
 */
export function useCurrencyFormat() {
    /**
     * Formatea un número a string con formato de moneda mexicana
     * @param {number|string} value - Valor numérico
     * @param {boolean} includeSymbol - Si incluir el símbolo $
     * @returns {string} Valor formateado: "1,000.00" o "$1,000.00"
     */
    const formatMoney = (value, includeSymbol = false) => {
        const num = parseFloat(value) || 0;
        const formatted = num.toLocaleString('es-MX', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
        return includeSymbol ? `$${formatted}` : formatted;
    };

    /**
     * Convierte un string con formato de moneda a número
     * @param {string} formatted - String con formato "1,000.50" o "$1,000.50"
     * @returns {number} Valor numérico: 1000.50
     */
    const parseMoney = (formatted) => {
        if (!formatted) return 0;
        // Quitar símbolo $ y comas, dejar solo números y punto
        const cleaned = String(formatted).replace(/[$,]/g, '');
        return parseFloat(cleaned) || 0;
    };

    /**
     * Convierte a centavos (para guardar en BD)
     * @param {number|string} value - Valor en pesos
     * @returns {number} Valor en centavos
     */
    const toCents = (value) => {
        const num = typeof value === 'string' ? parseMoney(value) : (parseFloat(value) || 0);
        return Math.round(num * 100);
    };

    /**
     * Convierte de centavos a pesos
     * @param {number} cents - Valor en centavos
     * @returns {number} Valor en pesos
     */
    const fromCents = (cents) => {
        return (parseInt(cents) || 0) / 100;
    };

    /**
     * Formatea mientras el usuario escribe (para inputs)
     * Permite escribir números y va formateando con comas
     * @param {string} value - Valor actual del input
     * @returns {string} Valor formateado
     */
    const formatInput = (value) => {
        if (!value) return '';

        // Quitar todo excepto números y punto
        let num = String(value).replace(/[^\d.]/g, '');

        // Manejar múltiples puntos - solo permitir uno
        const parts = num.split('.');
        if (parts.length > 2) {
            num = parts[0] + '.' + parts.slice(1).join('');
        }

        // Separar parte entera y decimal
        const [intPart, decPart] = num.split('.');

        // Formatear parte entera con comas
        const formattedInt = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        // Limitar decimales a 2
        if (decPart !== undefined) {
            return formattedInt + '.' + decPart.slice(0, 2);
        }

        return formattedInt;
    };

    /**
     * Valida si un string tiene formato de moneda válido
     * @param {string} value - Valor a validar
     * @returns {boolean}
     */
    const isValidMoney = (value) => {
        if (!value) return true;
        const cleaned = String(value).replace(/,/g, '');
        return !isNaN(parseFloat(cleaned)) && isFinite(cleaned);
    };

    /**
     * Formatea para mostrar en tabla/readonly (siempre con $ y 2 decimales)
     * @param {number|string} value - Valor
     * @returns {string} "$1,000.00"
     */
    const displayMoney = (value) => {
        return formatMoney(value, true);
    };

    return {
        formatMoney,
        parseMoney,
        toCents,
        fromCents,
        formatInput,
        isValidMoney,
        displayMoney,
    };
}

export default useCurrencyFormat;
