import axios from 'axios'
import api from './api'
import store from './vuex/store'
import { client } from './config'

const instance = axios.create({
  headers: {
    'Content-Type': 'application/json'
  }
})

instance.interceptors.request.use(
  config => {
    const token = localStorage.getItem('user')
    if (token) {
      const parsed = JSON.parse(token)
      config.headers.Authorization = 'Bearer ' + parsed.access_token
    }
    const locale = localStorage.getItem('locale')
    if (locale) {
      config.headers['Accept-Language'] = locale
    }

    return config
  },
  error => {
    Promise.reject(error)
  }
)

instance.interceptors.response.use(
  response => {
    return response
  },
  error => {
    const originalRequest = error.config
    const apiFind = api.find(o => o.name === 'refreshToken')
    if (error.response.status === 401 && originalRequest.url === apiFind.url) {
      store.dispatch('logout')
    } else if (error.response.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true
      const refreshToken = localStorage.getItem('user')
      const parsed = JSON.parse(refreshToken)
      return instance.post(apiFind.url,
        {
          grant_type: 'refresh_token',
          client_id: client.id,
          client_secret: client.secret,
          refresh_token: parsed.refresh_token
        })
        .then(res => {
          if (res.status === 201 || res.status === 200) {
            store.commit('setUserData', res.data)
            originalRequest.headers.Authorization = 'Bearer ' + res.data.access_token
            return axios(originalRequest)
          }
        }).catch()
    }
    return Promise.reject(error)
  })

export default instance
