export interface User {
  id: number;
  name: string;
  email: string;
  profiles: Profile[];
}

export interface RegisterData {
  name: string;
  email: string;
  password: string;
  passwordConfirmation: string;
}

export interface LoginData {
  email: string;
  password: string;
}

export interface AuthResponse {
  user: User;
  accessToken: string;
}

export interface UpdateUserData {
  name?: string;
  email?: string;
  password?: string;
  passwordConfirmation?: string;
}

export interface Profile {
  id: number;
  name: string;
  description?: string;
}