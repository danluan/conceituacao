export interface User {
  id: number;
  name: string;
  email: string;
  profileId: number;
  createdAt: string;
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

export interface UpdateProfileData {
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