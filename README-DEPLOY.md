# 🚀 Cinema Backend Deployment

## Manual Setup (One-time)
1. Buat ECS Service di AWS Console
2. Pilih cluster: `cinema-cluster`
3. Service name: `cinema-service`
4. Task definition: `cinema-backend`

## Auto Deploy
Setelah service dibuat manual, GitHub Actions akan otomatis update service setiap push ke main branch.

## API Endpoints
Lihat `API_DOCUMENTATION.md` untuk daftar lengkap endpoint.

## Database
RDS MySQL sudah dikonfigurasi di task definition.