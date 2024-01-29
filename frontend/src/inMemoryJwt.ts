const inMemoryJWTManager = () => {

    const getToken = () => localStorage.getItem("jwt_beapp");

    const setToken = (token: string): boolean => {
        localStorage.setItem("jwt_beapp", token)
        return true;
    };

    const ereaseToken = () : boolean => {
        localStorage.removeItem("jwt_beapp");
        return true;
    }

    return {
        ereaseToken,
        getToken,
        setToken,
    }
};

export default inMemoryJWTManager();
