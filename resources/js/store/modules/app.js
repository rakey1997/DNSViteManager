import { login as loginApi } from "../../api/login";
import router from "../../router";
import { setTokenTime } from "../../utils/auth";
import { setCookie, getCookie, delCookie } from "../../api/cookie";

export default {
    namespaced: true,
    state: () => ({
        userid: localStorage.getItem("userid") || "",
        role: localStorage.getItem("role") || "",
        api_token: localStorage.getItem("api_token") || "",
        siderType: true,
        lang: localStorage.getItem("lang") || "zh",
    }),
    mutations: {
        setUserID(state, userid) {
            state.userid = userid;
            localStorage.setItem("userid", userid);
        },
        setRole(state, role) {
            state.role = role;
            localStorage.setItem("role", role);
        },
        setToken(state, api_token) {
            state.api_token = api_token;
            localStorage.setItem("api_token", api_token);
        },
        changeSiderType(state) {
            state.siderType = !state.siderType;
        },
        changeLang(state, lang) {
            state.lang = lang;
        },
    },
    actions: {
        login({ commit }, loginForm) {
            return new Promise((resolve, reject) => {
                loginApi(loginForm)
                    .then((res) => {
                        commit("setUserID", res.userid);
                        commit("setRole", res.role);
                        commit("setToken", res.api_token);
                        setTokenTime();
                        router.replace("/");
                        ElMessage({
                            message: "登录成功",
                            type: "success",
                        });
                        resolve();
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },
        logout({ commit }) {
            commit("setUserID", "");
            commit("setRole", "");
            commit("setToken", "");
            delCookie("laravel_session");
            localStorage.clear();
            router.replace("/login");
        },
    },
};
