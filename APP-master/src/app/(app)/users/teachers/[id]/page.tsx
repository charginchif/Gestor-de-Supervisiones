
"use client"

import { useParams } from "next/navigation"
import { AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, ReferenceLine, Dot } from "recharts"
import { Star, ShieldCheck, BookUser, Library } from "lucide-react"
import React, { useMemo, useState } from "react"

import {
  users,
  supervisions,
  evaluations,
  subjects as allSubjects,
  schedules,
  groups as allGroups,
} from "@/lib/data"
import { Evaluation } from "@/lib/modelos"
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"
import { Separator } from "@/components/ui/separator"
import { Badge } from "@/components/ui/badge"
import { format } from "date-fns"
import { es } from "date-fns/locale"
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"
import { ProgressRing } from "@/components/ui/progress-ring"
import { Button } from "@/components/ui/button"
import { useIsMobile } from "@/hooks/use-mobile"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"

const getScoreColor = (score: number) => {
  if (score < 60) return 'hsl(var(--destructive))';
  if (score < 80) return 'hsl(var(--warning))';
  return 'hsl(var(--success))'; 
};

const CustomDot = (props: any) => {
    const { cx, cy, payload } = props;
    if (!payload) return null;

    const color = getScoreColor(payload.Calificación);

    return (
        <Dot
            cx={cx}
            cy={cy}
            r={5}
            strokeWidth={2}
            fill="#fff"
            stroke={color}
        />
    );
};

interface GroupEvaluationData {
  groupName: string;
  careerName: string;
  latestAverageRating: number;
  performanceData: { date: string; Calificación: number }[];
}


export default function TeacherProfilePage() {
  const params = useParams();
  const teacherId = Number(params.id);
  const [activeTab, setActiveTab] = useState<'supervisions' | 'evaluations' | 'subjects'>('supervisions');
  const isMobile = useIsMobile()

  const teacherData = useMemo(() => {
    const teacherUser = users.find(
      (user) => user.id === teacherId && user.rol === "docente"
    );
    
    if (!teacherUser) return null;

    const teacherFullName = `${teacherUser.nombre} ${teacherUser.apellido_paterno} ${teacherUser.apellido_materno}`.trim()

    const teacherSupervisions = supervisions.filter(
      (s) => s.teacher === teacherFullName
    );
    const teacherEvaluations = evaluations.filter(
      (e) => e.teacherName === teacherFullName
    );
    
    const teacherSchedules = schedules.filter(s => s.teacherId === teacherUser.id);
    const subjectIds = [...new Set(teacherSchedules.map(s => s.subjectId))];
    const teacherSubjects = allSubjects.filter(s => subjectIds.includes(s.id));
    
    const completedSupervisions = teacherSupervisions.filter(s => s.status === 'Completada' && s.score !== undefined);

    const supervisionPerformanceData = completedSupervisions
      .sort((a, b) => (a.date?.getTime() || 0) - (b.date?.getTime() || 0))
      .map(s => ({
        date: s.date ? format(s.date, "dd/MM/yy") : 'N/A',
        Calificación: s.score,
      }));

    const averageSupervisionScore = completedSupervisions.length > 0 
      ? Math.round(completedSupervisions.reduce((acc, s) => acc + s.score!, 0) / completedSupervisions.length)
      : 0;

    const averageEvaluationScore = teacherEvaluations.length > 0
        ? Math.round(teacherEvaluations.reduce((acc, e) => acc + e.overallRating, 0) / teacherEvaluations.length)
        : 0;

    const evaluationsByGroup = teacherEvaluations.reduce((acc, evaluation) => {
        const groupName = evaluation.groupName || 'Grupo Desconocido';
        if (!acc[groupName]) {
            acc[groupName] = [];
        }
        acc[groupName].push(evaluation);
        return acc;
    }, {} as Record<string, Evaluation[]>);


    const groupPerformance: GroupEvaluationData[] = Object.entries(evaluationsByGroup).map(([groupName, groupEvaluations]) => {
        const groupDetails = allGroups.find(g => g.name === groupName);
        
        const evaluationsByBatch = groupEvaluations.reduce((acc, ev) => {
            const batchId = ev.evaluationBatchId || new Date(ev.date).toISOString().split('T')[0];
            if (!acc[batchId]) {
                acc[batchId] = { date: new Date(ev.date), ratings: [] };
            }
            acc[batchId].ratings.push(ev.overallRating);
            return acc;
        }, {} as Record<string, { date: Date, ratings: number[] }>);

        const performanceData = Object.values(evaluationsByBatch)
            .sort((a, b) => a.date.getTime() - b.date.getTime())
            .map(batch => ({
                date: format(batch.date, "dd/MM/yy"),
                Calificación: Math.round(batch.ratings.reduce((sum, r) => sum + r, 0) / batch.ratings.length),
            }));

        const latestAverageRating = performanceData.length > 0 ? performanceData[performanceData.length - 1].Calificación : 0;
        
        return {
            groupName,
            careerName: groupDetails?.career || 'Carrera Desconocida',
            latestAverageRating,
            performanceData
        };
    });


    return {
      teacher: teacherUser,
      teacherFullName,
      teacherSupervisions,
      teacherEvaluations,
      teacherSubjects,
      supervisionPerformanceData,
      averageSupervisionScore,
      averageEvaluationScore,
      groupPerformance,
    }
  }, [teacherId]);

  if (!teacherData) {
    return (
      <div className="flex items-center justify-center h-full">
        <p>Docente no encontrado.</p>
      </div>
    )
  }

  const { teacher, teacherFullName, teacherSupervisions, teacherSubjects, supervisionPerformanceData, averageSupervisionScore, averageEvaluationScore, groupPerformance } = teacherData;
  
  const renderNav = () => {
    const navOptions = [
      { value: 'supervisions', label: 'Supervisiones', icon: ShieldCheck },
      { value: 'evaluations', label: 'Evaluaciones', icon: BookUser },
      { value: 'subjects', label: 'Materias', icon: Library },
    ];

    if (isMobile) {
      return (
        <Select onValueChange={(value) => setActiveTab(value as any)} defaultValue={activeTab}>
          <SelectTrigger className="w-full">
            <SelectValue placeholder="Seleccionar sección" />
          </SelectTrigger>
          <SelectContent>
            {navOptions.map(opt => <SelectItem key={opt.value} value={opt.value}>{opt.label}</SelectItem>)}
          </SelectContent>
        </Select>
      );
    }

    return (
      <div className="flex items-center gap-2">
        {navOptions.map(opt => {
          const Icon = opt.icon;
          return (
            <Button
              key={opt.value}
              variant={activeTab === opt.value ? 'default' : 'outline'}
              onClick={() => setActiveTab(opt.value as any)}
            >
              <Icon className="h-4 w-4 mr-2" />
              {opt.label}
            </Button>
          );
        })}
      </div>
    );
  };

  return (
    <div className="flex flex-col gap-8">
      <div className="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between gap-4">
        <div className="flex items-center gap-4">
            <Avatar className="h-20 w-20">
                <AvatarImage src={`https://placehold.co/100x100.png?text=${teacher.nombre.charAt(0)}`} alt={teacherFullName} data-ai-hint="person avatar" />
                <AvatarFallback>{teacher.nombre.charAt(0)}</AvatarFallback>
            </Avatar>
            <div>
                <h1 className="font-headline text-3xl font-bold tracking-tight text-white">
                    {teacherFullName}
                </h1>
                <p className="text-muted-foreground">{teacher.correo}</p>
                <p className="text-xs text-muted-foreground mt-1">
                Miembro desde: {new Date(teacher.fecha_registro).toLocaleDateString("es-ES")}
                </p>
            </div>
        </div>
        {renderNav()}
      </div>

      {activeTab === 'supervisions' && (
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div className="lg:col-span-3">
                <Card className="rounded-xl">
                    <CardHeader>
                        <div className="flex justify-between items-start">
                            <div>
                                <CardTitle>Progresión de Rendimiento</CardTitle>
                                <CardDescription>Evolución del rendimiento a través de las supervisiones completadas.</CardDescription>
                            </div>
                            <div className="flex flex-col items-center">
                                <ProgressRing value={averageSupervisionScore} />
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent className="h-80 w-full pr-8">
                        {supervisionPerformanceData.length > 0 ? (
                            <ResponsiveContainer width="100%" height="100%">
                                <AreaChart
                                    data={supervisionPerformanceData}
                                    margin={{
                                        top: 10,
                                        right: 30,
                                        left: 0,
                                        bottom: 0,
                                    }}
                                >
                                    <CartesianGrid strokeDasharray="3 3" stroke="hsl(var(--border) / 0.5)" />
                                    <XAxis dataKey="date" stroke="hsl(var(--foreground))" fontSize={12} tickLine={false} axisLine={false} />
                                    <YAxis stroke="hsl(var(--foreground))" domain={[0, 100]} tickFormatter={(value) => `${value}%`} fontSize={12} tickLine={false} axisLine={false} />
                                    <Tooltip
                                        contentStyle={{
                                            backgroundColor: 'hsl(var(--background) / 0.8)',
                                            borderColor: 'hsl(var(--border))',
                                            color: 'hsl(var(--foreground))',
                                            borderRadius: 'var(--radius)'
                                        }}
                                    />
                                    <ReferenceLine y={60} stroke="hsl(var(--destructive))" strokeWidth={2} />
                                    <Area 
                                        type="monotone" 
                                        dataKey="Calificación" 
                                        stroke="hsl(var(--primary))" 
                                        fill="hsl(var(--primary) / 0.2)"
                                        dot={<CustomDot />}
                                    />
                                </AreaChart>
                            </ResponsiveContainer>
                        ) : (
                             <div className="flex items-center justify-center h-full border-2 border-dashed border-muted rounded-xl">
                                <p className="text-muted-foreground">Aún no hay datos de rendimiento.</p>
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>

            <div className="lg:col-span-3">
            <Card className="rounded-xl">
                <CardHeader>
                <CardTitle>Historial de Supervisión</CardTitle>
                <CardDescription>
                    Supervisiones programadas y completadas para este docente.
                </CardDescription>
                </CardHeader>
                <CardContent>
                <Table>
                    <TableHeader>
                    <TableRow>
                        <TableHead>Carrera</TableHead>
                        <TableHead>Coordinador</TableHead>
                        <TableHead>Fecha</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead className="text-right">Calificación</TableHead>
                    </TableRow>
                    </TableHeader>
                    <TableBody>
                    {teacherSupervisions.length > 0 ? teacherSupervisions.map((supervision) => (
                        <TableRow key={supervision.id}>
                        <TableCell className="font-medium">
                            {supervision.career}
                        </TableCell>
                        <TableCell>{supervision.coordinator}</TableCell>
                        <TableCell>
                            {supervision.date ? format(supervision.date, "P", { locale: es }) : 'N/A'}
                        </TableCell>
                        <TableCell>
                            <Badge
                            variant={
                                supervision.status === "Programada"
                                ? "warning"
                                : "success"
                            }
                            >
                            {supervision.status}
                            </Badge>
                        </TableCell>
                        <TableCell className="text-right font-mono">
                            {supervision.score !== undefined ? `${supervision.score}%` : "N/A"}
                        </TableCell>
                        </TableRow>
                    )) : (
                        <TableRow>
                            <TableCell colSpan={5} className="text-center h-24">No hay supervisiones registradas.</TableCell>
                        </TableRow>
                    )}
                    </TableBody>
                </Table>
                </CardContent>
            </Card>
            </div>
        </div>
      )}

      {activeTab === 'evaluations' && (
        <div className="lg:col-span-3 grid grid-cols-1 gap-8">
            {groupPerformance.length > 0 ? (
                groupPerformance.map(groupData => (
                    <Card key={groupData.groupName} className="rounded-xl">
                        <CardHeader>
                            <div className="flex justify-between items-start">
                                <div>
                                    <CardTitle>Rendimiento: {groupData.groupName}</CardTitle>
                                    <CardDescription>{groupData.careerName}</CardDescription>
                                </div>
                                <div className="flex flex-col items-center">
                                    <ProgressRing value={groupData.latestAverageRating} />
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent className="h-80 w-full pr-8">
                             {groupData.performanceData.length > 0 ? (
                                <ResponsiveContainer width="100%" height="100%">
                                    <AreaChart
                                        data={groupData.performanceData}
                                        margin={{ top: 10, right: 30, left: 0, bottom: 0 }}
                                    >
                                        <CartesianGrid strokeDasharray="3 3" stroke="hsl(var(--border) / 0.5)" />
                                        <XAxis dataKey="date" stroke="hsl(var(--foreground))" fontSize={12} tickLine={false} axisLine={false} />
                                        <YAxis stroke="hsl(var(--foreground))" domain={[0, 100]} tickFormatter={(value) => `${value}%`} fontSize={12} tickLine={false} axisLine={false} />
                                        <Tooltip
                                            contentStyle={{
                                                backgroundColor: 'hsl(var(--background) / 0.8)',
                                                borderColor: 'hsl(var(--border))',
                                                color: 'hsl(var(--foreground))',
                                                borderRadius: 'var(--radius)'
                                            }}
                                        />
                                        <ReferenceLine y={60} stroke="hsl(var(--destructive))" strokeWidth={2} />
                                        <Area type="monotone" dataKey="Calificación" stroke="hsl(var(--primary))" fill="hsl(var(--primary) / 0.2)" dot={<CustomDot />} />
                                    </AreaChart>
                                </ResponsiveContainer>
                            ) : (
                                <div className="flex items-center justify-center h-full border-2 border-dashed border-muted rounded-xl">
                                    <p className="text-muted-foreground">Aún no hay datos de evaluaciones para este grupo.</p>
                                </div>
                            )}
                        </CardContent>
                    </Card>
                ))
            ) : (
                 <Card className="rounded-xl">
                    <CardHeader>
                        <CardTitle>Rendimiento de Evaluaciones</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="flex items-center justify-center h-24 border-2 border-dashed border-muted rounded-xl">
                            <p className="text-muted-foreground">No hay evaluaciones de alumnos todavía.</p>
                        </div>
                    </CardContent>
                </Card>
            )}
        </div>
      )}
      
      {activeTab === 'subjects' && (
        <div className="lg:col-span-3">
            <Card className="rounded-xl">
                <CardHeader>
                    <CardTitle>Materias Impartidas</CardTitle>
                    <CardDescription>
                        Lista de materias que este docente imparte actualmente.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Materia</TableHead>
                                <TableHead>Carrera</TableHead>
                                <TableHead>Grado</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {teacherSubjects.map((subject) => (
                                <TableRow key={subject.id}>
                                    <TableCell className="font-medium">{subject.name}</TableCell>
                                    <TableCell>{subject.career}</TableCell>
                                    <TableCell>{subject.semester}°</TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
      )}

    </div>
  )
}
