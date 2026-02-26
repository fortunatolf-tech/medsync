import React, { useState, useEffect } from 'react';

const CookieBanner = () => {
    const [isVisible, setIsVisible] = useState(false);
    const [preferences, setPreferences] = useState({
        essential: true, // Always true
        analytics: false,
        marketing: false,
    });

    useEffect(() => {
        const consent = localStorage.getItem('medsync_cookie_consent');
        if (!consent) {
            setIsVisible(true);
        }
    }, []);

    const handleAcceptAll = () => {
        const allConsented = { essential: true, analytics: true, marketing: true };
        localStorage.setItem('medsync_cookie_consent', JSON.stringify(allConsented));
        setIsVisible(false);
    };

    const handleSavePreferences = () => {
        localStorage.setItem('medsync_cookie_consent', JSON.stringify(preferences));
        setIsVisible(false);
    };

    if (!isVisible) return null;

    return (
        <div style={styles.banner}>
            <div style={styles.content}>
                <h3>Sua Privacidade é Importante (LGPD)</h3>
                <p>
                    Utilizamos cookies para melhorar sua experiência no MedSync. Os cookies essenciais são necessários para
                    o funcionamento do site. Você pode escolher quais outros cookies deseja habilitar.
                </p>
                <div style={styles.options}>
                    <label>
                        <input type="checkbox" checked disabled />
                        Essenciais (Obrigatórios)
                    </label>
                    <label>
                        <input
                            type="checkbox"
                            checked={preferences.analytics}
                            onChange={(e) => setPreferences({ ...preferences, analytics: e.target.checked })}
                        />
                        Analytics (Desempenho)
                    </label>
                    <label>
                        <input
                            type="checkbox"
                            checked={preferences.marketing}
                            onChange={(e) => setPreferences({ ...preferences, marketing: e.target.checked })}
                        />
                        Marketing
                    </label>
                </div>
                <div style={styles.buttons}>
                    <button style={styles.buttonSave} onClick={handleSavePreferences}>Salvar Minhas Preferências</button>
                    <button style={styles.buttonAccept} onClick={handleAcceptAll}>Aceitar Todos os Cookies</button>
                </div>
            </div>
        </div>
    );
};

const styles = {
    banner: {
        position: 'fixed' as const,
        bottom: 0,
        left: 0,
        width: '100%',
        backgroundColor: '#fff',
        boxShadow: '0 -2px 10px rgba(0,0,0,0.1)',
        zIndex: 9999,
        padding: '20px',
        boxSizing: 'border-box' as const,
    },
    content: {
        maxWidth: '1200px',
        margin: '0 auto',
        fontFamily: 'Arial, sans-serif',
    },
    options: {
        display: 'flex',
        gap: '20px',
        margin: '15px 0',
    },
    buttons: {
        display: 'flex',
        gap: '10px',
        marginTop: '15px',
    },
    buttonSave: {
        padding: '10px 20px',
        border: '1px solid #0056b3',
        backgroundColor: 'transparent',
        color: '#0056b3',
        cursor: 'pointer',
        borderRadius: '4px',
    },
    buttonAccept: {
        padding: '10px 20px',
        border: 'none',
        backgroundColor: '#0056b3',
        color: 'white',
        cursor: 'pointer',
        borderRadius: '4px',
    }
};

export default CookieBanner;
