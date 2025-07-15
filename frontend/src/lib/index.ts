import { v4 as uuid } from 'uuid'
import Slug from "slug"

export let set = (token = "") => {
  const name = import.meta.env.VITE_AUTH_COOKIE_NAME
  const salt = Slug(uuid() + uuid() + uuid() + uuid() + uuid())
  const date = new Date();
  date.setDate(date.getDate() + 7);

  return `${name}=${token}||${salt}; Expires=${date.toUTCString()}; Path=/; HttpOnly; Secure`;

}

export let get = (cookiesString: string|null, name = undefined) => {
  if (cookiesString == null || cookiesString == "") return null

  let cookieArr = cookiesString.split(";")
    .map(function (cookieString) {
      return cookieString.trim().split("=");
    })
    .reduce(function (acc, curr) {
      acc[curr[0]] = curr[1];
      return acc
    }, {});

  return name ? (cookieArr[name] ? cookieArr[name] : false) : cookieArr

}