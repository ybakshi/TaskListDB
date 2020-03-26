CREATE TABLE [dbo].[tasks]
(
[id] [int] NULL,
[task] [varchar] (255) COLLATE SQL_Latin1_General_CP1_CI_AS NULL
) ON [PRIMARY]
GO
EXEC sp_addextendedproperty N'N', N'1', 'SCHEMA', N'dbo', 'TABLE', N'tasks', 'COLUMN', N'id'
GO
EXEC sp_addextendedproperty N'task', N'nkkklk', 'SCHEMA', N'dbo', 'TABLE', N'tasks', 'COLUMN', N'id'
GO
